<?php
header('Content-Type: text/plain; charset=UTF-8');
require_once $_SERVER['DOCUMENT_ROOT'].'/data/conf.php';
$host = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^0-9A-Za-z-.:]/","",mb_strtolower(strip_tags(trim($_SERVER['HTTP_HOST'])), 'UTF-8')) : 'localhost';
$scheme = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? trim(strip_tags($_SERVER['HTTP_X_FORWARDED_PROTO'])) : $_SERVER['REQUEST_SCHEME'];

// доры могут работать только через клаудфлар:
if ($cloudflare_only == 1) {
if (!isset($_SERVER['HTTP_CF_RAY'])) die();
}

echo 'User-Agent: *
Disallow: /?
Sitemap: '.$scheme.'://'.$host.'/sitemap.xml
';
