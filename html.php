<?php
srand(crc32($_SERVER['HTTP_HOST']));
require_once $_SERVER['DOCUMENT_ROOT'].'/data/conf.php';

// подключаемся к базе:
$db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/data/'.$db_file); 
$db->busyTimeout(1000);
$db->exec("PRAGMA journal_mode = OFF;");
$db->exec("PRAGMA synchronous = OFF;");

// максимальный номер из базы:
$last = @$db->querySingle("SELECT id FROM books ORDER BY id DESC;", true);
if (!isset($last['id'])) die('где кеи блэт?');
$max_num = (int)$last['id'];

// номера страниц домена:
$num = array();
for ($x=0; $x<$lnk_max; $x++) {$num[] = rand(1, $max_num);}

// доры могут работать только через клаудфлар:
if ($cloudflare_only == 1) {
if (!isset($_SERVER['HTTP_CF_RAY'])) die();
}

$list = $db->query("SELECT title FROM books WHERE id IN (".implode(",", $num).");");

echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Sitemap</title>
</head>
<body>';
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
echo '<a href="/'.urlencode(str_replace(' ', '_', $echo['title'])).'.html">'.$echo['title'].'</a> 
';}
echo '</body>
</html>';
