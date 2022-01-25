<?php
// Last update date: 2020.04.28
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Fake bots');

// номер страницы пагинации:
$n = isset($_GET['n']) ? preg_replace("/[^0-9]/","",trim($_GET['n'])) : 0;

$list = $antibot_db->query("SELECT rowid, * FROM fake ORDER BY rowid DESC LIMIT ".$n.", 100;"); 

$content = '
<p>'.abTranslate('This list may include not only fake bots, but also good bots, if the parameters for defining good bots are not correctly or completely configured. For example, not all IP subnets are added to the list in the config or not all PTRs.').'</p>
<table class="table table-bordered table-hover table-sm">
<thead class="thead-light">
<tr>
<th>#</th>
<th>'.abTranslate('Date').'</th>
<th>'.abTranslate('Country').'</th>
<th>IP (PTR)</th>
<th>User Agent</th>
</tr>
</thead>
<tbody>
';
$i = 0;
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
$content .= '<tr>
<td>'.$echo['rowid'].'</td>
<td>'.date("Y.m.d", strtotime($echo['date'])).'<br />'.$echo['time'].'</td>
<td><img src="flags/'.$echo['country'].'.png" class="pngflag" title="'.$echo['country'].'" /> <strong>'.$echo['country'].'</strong></td>
<td>'.$echo['ip'].'</a> ('.$echo['ptr'].') <a href="https://antibot.cloud/whois-'.$echo['ip'].'.html" target="_blank" rel="noopener">whois</a></td>
<td>'.$echo['useragent'].'</td>
</tr>';
$i++;
}
$content .= '</tbody>
</table>';
if ($i == 100) {
$content .= '<center><a href="?page=fake&n='.($n+100).'" class="btn btn-outline-info">'.abTranslate('Show more').'</a><br /><small><a href="?page=fake">'.abTranslate('To the begining').'</a></small></center>';
} else {
$content .= '<center><small><a href="?page=fake">'.abTranslate('To the begining').'</a></small></center>';
}
$content .= '
<p><form class="form-inline" action="?page=clearfake" method="post">
<input name="page" type="hidden" value="fake">
<input style="cursor:pointer;" class="btn btn-sm btn-danger" type="submit" name="submit" value="'.abTranslate('Empty the records').'">
</form></p>
<p>'.abTranslate('Delete all of these entries. Reduce the size of the database file (VACUUM), may take a long time.').'</p>
';
