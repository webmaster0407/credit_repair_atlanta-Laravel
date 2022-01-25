<?php
// Last update date: 2020.04.02
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Change the interface language');

$lang = isset($_GET['lang']) ? trim(preg_replace("/[^a-z]/","", $_GET['lang'])) : 'en';
$referer = isset($_SERVER['HTTP_REFERER']) ? trim(strip_tags($_SERVER['HTTP_REFERER'])) : '?page=index';

setcookie('lang_code', $lang, time()+31536000, '/'); // на год

header('HTTP/1.1 301 Moved Permanently');
header('Location: '.$referer);
