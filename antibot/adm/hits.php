<?php
// Last update date: 2020.05.16
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Query Log');

// номер страницы пагинации:
$n = isset($_GET['n']) ? preg_replace("/[^0-9]/","",trim($_GET['n'])) : 0;

$search = isset($_GET['search']) ? trim(strip_tags($_GET['search'])) : '';
$search = $antibot_db->escapeString($search);
$table = isset($_GET['table']) ? preg_replace("/[^a-z]/","",trim($_GET['table'])) : '';
$status = isset($_GET['status']) ? preg_replace("/[^0-9]/","",trim($_GET['status'])) : '';
$operator = isset($_GET['operator']) ? preg_replace("/[^a-z]/","",trim($_GET['operator'])) : '';

if ($search != '') {
if ($operator == 'equally') {$q = "='".$search."'";} else {$q = "LIKE '%".$search."%'";}
$list = $antibot_db->query("SELECT rowid, * FROM hits WHERE ".(($status != '') ? "passed='".$status."' AND" : '')." ".$table." ".$q." ORDER BY rowid DESC LIMIT ".$n.", 100;"); 
} else {
$list = $antibot_db->query("SELECT rowid, * FROM hits ".(($status != '') ? "WHERE passed='".$status."'" : '')." ORDER BY rowid DESC LIMIT ".$n.", 100;"); 
}

if ($list === false) {
    var_dump($antibot_db->lastErrorCode());
    var_dump($antibot_db->lastErrorMsg());
die();
}

$content = '
<form class="form-inline" action="?page=hits" method="get">
<input class="form-control mx-sm-3 form-control-sm" name="page" type="hidden" value="hits">
'.abTranslate('Search:').' <input class="form-control mx-sm-3 form-control-sm" name="search" type="text" value="">
'.abTranslate('status:').'
<select class="form-control mx-sm-3 form-control-sm" name="status">
<option value="">'.abTranslate('any').'</option>
<option value="0">stop</option>
<option value="1">auto</option>
<option value="2">post</option>
<option value="3">local</option>
</select> 
'.abTranslate('table:').'
<select class="form-control mx-sm-3 form-control-sm" name="table">
<option value="ip">IP</option>
<option value="ptr">PTR</option>
<option value="useragent">useragent</option>
<option value="uid">uid</option>
<option value="cid">cid</option>
<option value="country">country</option>
<option value="referer">referer</option>
<option value="page">page</option>
<option value="lang">lang</option>
</select>
'.abTranslate('operator:').' 
<select class="form-control mx-sm-3 form-control-sm" name="operator">
<option value="equally">'.abTranslate('Strictly equal').'</option>
<option value="contains">'.abTranslate('Contains').'</option>
</select>
<input style="cursor:pointer;" class="btn btn-sm btn-primary" type="submit" name="submit" value="'.abTranslate('Search').'">
</form>
<br />
<table class="table table-bordered table-hover table-sm">
<thead class="thead-light">
<tr>
<th>'.abTranslate('Status').'</th>
<th>IP (PTR) & User Agent & Accept Language</th>
<th>Referer & Page & UID</th>
</tr>
</thead>
<tbody>
';
$i = 0;
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
if ($echo['passed'] == 0) {$passed = '<span style="color:red;">stop</span>';} elseif ($echo['passed'] == 1) {$passed = '<span style="color:green;">auto</span>';} elseif ($echo['passed'] == 2) {$passed = '<span style="color:teal;">post</span>';} elseif ($echo['passed'] == 3) {$passed = '<span style="color:black;">local</span>';}
$content .= '<tr>
<td>'.date("Y.m.d", strtotime($echo['date'])).' '.$echo['time'].'<br />
'.$passed.' '.round($echo['generation'], 3);
if (isset($ab_config['recaptcha_secret'])) {
$content .= '<br /><span class="text-secondary">
'.$echo['js_w'].'x'.$echo['js_h'].'<br />
'.$echo['js_cw'].'x'.$echo['js_ch'].'<br />
'.$echo['js_co'].' '.$echo['js_pi'].'<br />
RE score: '.$echo['recaptcha'].'<br />
'.$echo['proto'].'
</span>
';
}
$content .= '</td>
<td><img src="flags/'.$echo['country'].'.png" class="pngflag" title="'.$echo['country'].'" /> <strong>'.$echo['country'].'</strong> <a href="?page=hits&search='.$echo['ip'].'&table=ip&operator=equally" title="'.abTranslate('selection by:').' IP">'.$echo['ip'].'</a> ('.$echo['ptr'].') <a href="https://antibot.cloud/whois-'.$echo['ip'].'.html" target="_blank" rel="noopener">whois</a><br />
<small>'.$echo['useragent'].'</small><br /><em>'.wordwrap($echo['lang'], 10, " ", true).'</em>
</td>
<td><small>
R: <a href="'.$echo['referer'].'" target="_blank" rel="noopener" title="Referer">'.mb_strimwidth($echo['referer'], 0, 60, '...', 'utf-8').'</a><br />
P: <a href="'.$echo['page'].'" target="_blank" rel="noopener" title="Page">'.mb_strimwidth($echo['page'], 0, 60, '...', 'utf-8').'</a><br />
uid: <a href="?page=hits&search='.$echo['uid'].'&table=uid&operator=equally" title="'.abTranslate('selection by:').' UID">'.$echo['uid'].'</a> <br />
cid: '.$echo['cid'].'</small></td>
</tr>';
$i++;
}
$content .= '</tbody>
</table>';
if ($i == 100) {
$content .= '<center><a href="?page=hits&n='.($n+100).'&status='.$status.'&search='.$search.'&table='.$table.'&operator='.$operator.'" class="btn btn-outline-info">'.abTranslate('Show more').'</a><br /><small><a href="?page=hits">'.abTranslate('To the begining').'</a></small></center>';
} else {
$content .= '<center><small><a href="?page=hits">'.abTranslate('To the begining').'</a></small></center>';
}
$content .= '<p><span style="color:red;">stop</span> - '.abTranslate('request to the AntiBot check page, check failed, the visitor remains on the check page.').'<br />
<span style="color:green;">auto</span> - '.abTranslate('request to the AntiBot check page, the check was completed automatically, the visitor received a redirect to the full page.').'<br />
<span style="color:teal;">post</span> - '.abTranslate('the request to the AntiBot check page, the check did not pass automatically, the visitor clicked on the website login button, the visitor received a redirect to the full page.').'<br />
<span style="color:black;">local</span> - '.abTranslate('request to a full page of the website, the visitor already had permission to access the website.').'<br />
'.abTranslate('The following is the time taken to generate the AntiBot check software.').'
</p>
<p><form class="form-inline" action="?page=clearhits" method="post">
<input name="page" type="hidden" value="hits">
<input style="cursor:pointer;" class="btn btn-sm btn-danger" type="submit" name="submit" value="'.abTranslate('Empty the records').'">
</form></p>
<p>'.abTranslate('Delete all of these entries. Reduce the size of the database file (VACUUM), may take a long time.').'</p>
';
