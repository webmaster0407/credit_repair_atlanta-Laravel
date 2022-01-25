<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/data/conf.php';

$time = time();
srand($time);
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
for ($x=0; $x<25; $x++) {$num[] = rand(1, $max_num);}

header('Content-Type: text/xml; charset=UTF-8');
$host = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^0-9A-Za-z-.:]/","",mb_strtolower(strip_tags(trim($_SERVER['HTTP_HOST'])), 'UTF-8')) : 'localhost';
$scheme = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? trim(strip_tags($_SERVER['HTTP_X_FORWARDED_PROTO'])) : $_SERVER['REQUEST_SCHEME'];

// доры могут работать только через клаудфлар:
if ($cloudflare_only == 1) {
if (!isset($_SERVER['HTTP_CF_RAY'])) die();
}

$list = $db->query("SELECT title FROM books WHERE id IN (".implode(",", $num).");");

echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<title>RSS</title>
<link>'.$scheme.'://'.$host.'/</link>
<atom:link href="'.$scheme.'://'.$host.'/rss.php" rel="self" type="application/rss+xml" />
<description></description>
';
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
echo '<item>
<title><![CDATA['.$echo['title'].']]></title>
<link>'.$scheme.'://'.$host.'/'.urlencode(str_replace(' ', '_', $echo['title'])).'.html</link>
<guid>'.$scheme.'://'.$host.'/'.urlencode(str_replace(' ', '_', $echo['title'])).'.html</guid>
<description> </description>
</item>
';
}
echo '</channel>
</rss>';
