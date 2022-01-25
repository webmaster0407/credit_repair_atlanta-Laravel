<?php
// Last update date: 2020.08.18
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Blocking and Permission Rules');

$list = $antibot_db->query("SELECT rowid, * FROM rules ORDER BY rowid DESC;"); 

$content = '<form class="form-inline" action="?page=addrule" method="post">
'.abTranslate('IP address:').' <input class="form-control mx-sm-3 form-control-sm" name="search" type="text" value="">
'.abTranslate('Comment:').' <input class="form-control mx-sm-3 form-control-sm" name="comment" type="text" value="">
'.abTranslate('Rule:').' 
<select class="form-control mx-sm-3 form-control-sm" name="rule">
<option value="white">'.abTranslate('White (allow)').'</option>
<option value="black">'.abTranslate('Black (block)').'</option>
</select>
<input style="cursor:pointer;" type="submit" name="submit" class="btn btn-sm btn-primary" value="'.abTranslate('Добавить').'">
</form>
<p><small><span style="color:green;">'.abTranslate('Your IP:').' <strong>'.$ab_config['ip'].'</strong></span> '.abTranslate('IPv4 address type «123.123.123.123» or IPv6 type «1234:abcd:1234:abcd:1234:abcd:1234:abcd» or in a shorter form - the first 3 parts of IPv4 address: «123.123.123» (if you need to add the entire subnet by mask /24) or for IPv6 the first 4 parts: «1234:abcd:1234:abcd» (subnet by mask /64).').'</small></p>
<hr />
<form class="form-inline" action="?page=addrule" method="post">
'.abTranslate('Condition:').' <input class="form-control mx-sm-3 form-control-sm" name="search" type="text" value="">
'.abTranslate('Comment:').' <input class="form-control mx-sm-3 form-control-sm" name="comment" type="text" value="">
'.abTranslate('Rule:').' 
<select class="form-control mx-sm-3 form-control-sm" name="rule">
<option value="black">'.abTranslate('Black (block)').'</option>
</select>
<input style="cursor:pointer;" type="submit" name="submit" class="btn btn-sm btn-primary" value="'.abTranslate('Добавить').'">
</form>

<p><small>'.abTranslate('Rules by country, language, referrer, ptr - you can add only to the black list.').'<br />
<span style="color:red;">'.abTranslate('Blocking by country').'</span> - '.abTranslate('add 2 uppercase country codes (example: US). List of country codes:').' <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements" target="_blank">ISO 3166-1 alpha-2</a><br />
<span style="color:red;">'.abTranslate('Blocking by browser language').'</span> - '.abTranslate('2 lowercase alphabetic language codes (example: en). List of language codes:').' <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank">ISO 639-1</a><br />
<span style="color:red;">'.abTranslate('Blocking by referrer').'</span> - '.abTranslate('add a domain (host only, without protocol and internal pages), in the form: spamsite.com').'<br />
<span style="color:red;">'.abTranslate('Blocking by PTR').'</span> - '.abTranslate('add a domain from PTR, but not the whole, but only 2 or 3 level domains, i.e. if in statistics you see a PTR of the form: «ec2-52-15-190-240.us-east-2.compute.amazonaws.com», then you need to add «compute.amazonaws.com» or «amazonaws.com».').' <a href="https://antibot.cloud/static/Bad_PTR.txt" target="_blank">'.abTranslate('My list is for PTR blocking.').'</a>
</small></p>
<p><a href="?page=exportrules">'.abTranslate('Export all rules from the database to a .txt file.').'</a> | 
<a href="?page=importrules">'.abTranslate('Import rules to the database.').'</a></p>
<table class="table table-bordered table-hover table-sm">
<thead class="thead-light">
<tr>
<th>#</th>
<th>'.abTranslate('Check condition').'</th>
<th>'.abTranslate('Rule').'</th>
<th>'.abTranslate('Comment').'</th>
<th>'.abTranslate('Remove').'</th>
</tr>
</thead>
<tbody>
';
$i = 0;
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
if ($echo['rule'] == 'white') {$style = 'style="color:green;"';} else {$style = 'style="color:red;"';}
$content .= '<tr>
<td>'.$echo['rowid'].'</td>
<td>'.$echo['search'].'</td>
<td '.$style.'>'.$echo['rule'].'</td>
<td><small>'.$echo['comment'].'</small></td>
<td><form action="?page=remove" method="post"><input name="id" type="hidden" value="'.$echo['rowid'].'"><input style="cursor:pointer;" type="submit" name="submit" class="btn btn-danger btn-sm" value="'.abTranslate('Remove').'" title="'.abTranslate('Remove').'"></form></td>
</tr>';
$i++;
}
$content .= '</tbody>
</table>
<p>'.abTranslate('Total rules:').' '.$i.'</p>
<p><strong>'.abTranslate('Check condition').'</strong> - '.abTranslate('data to search during the check (field values are unique).').'<br />
<strong>'.abTranslate('Rule').'</strong> - '.abTranslate('allow white list (white) or black list (black) prohibit.').'<br />
<strong>'.abTranslate('Comment').'</strong> - '.abTranslate('description or reason for adding.').'</p>
<p><form class="form-inline" action="?page=clearrules" method="post">
<input name="page" type="hidden" value="rules">
<input style="cursor:pointer;" class="btn btn-sm btn-danger" type="submit" name="submit" value="'.abTranslate('Remove all rules').'">
</form></p>';
