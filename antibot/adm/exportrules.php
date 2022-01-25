<?php
// Last update date: 2020.05.18
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Blocking and Permission Rules');

$list = $antibot_db->query("SELECT rowid, * FROM rules ORDER BY rowid DESC;"); 

$content = '';
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
$content .= $echo['search'].'|'.$echo['rule'].'|'.$echo['comment'].'
';
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=AntiBot-rules-'.$ab_config['host'].'-'.date('Y.m.d_h:i:s', $ab_config['time']).'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($content));
echo $content;
die();
