<?php
// Last update: 2020.05.01
// скрипт локальной проверки посетителя.
// если хотите модифицировать этот скрипт - переименуйте его и используйте под другим именем.

header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('X-Powered-CMS: AntiBot.Cloud (See: https://antibot.cloud/)');
header('X-Robots-Tag: noindex');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');

require_once(__DIR__.'/data/conf.php');
require_once(__DIR__.'/code/func.php');

if ($ab_config['check_url'] == '') die();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {die('{"error": "Error 0"}');}

$time = time();

// пустой реферер запросившего скрипт:
$referer = isset($_SERVER['HTTP_REFERER']) ? strip_tags(trim($_SERVER['HTTP_REFERER'])) : '';
if ($referer == '') {die('{"error": "Error 1"}');}

// пустой юзерагент:
$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? trim(strip_tags($_SERVER['HTTP_USER_AGENT'])) : '';
if ($useragent == '') {die('{"error": "Error 2"}');}

// язык у норм браузеров есть всегда:
$accept_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? trim(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE'])) : die();
if ($accept_lang == '') {die('{"error": "Error 3"}');}

// домен (host) с которого вызвали скрипт:
$refhost = parse_url($referer, PHP_URL_HOST);

// ---------------------------------------------------------------------

// POST данные:

// код страны:
$action = isset($_POST['action']) ? trim(preg_replace("/[^A-Z]/","", $_POST['action'])) : 'XX';
// рекапча токен:
$token = isset($_POST['token']) ? trim($_POST['token']) : die();
// полный хэш:
$h1 = isset($_POST['h1']) ? trim(preg_replace("/[^0-9a-z]/","", $_POST['h1'])) : die();
// хэш емейла:
$h2 = isset($_POST['h2']) ? trim(preg_replace("/[^0-9a-z]/","", $_POST['h2'])) : die();
// укороченный ип из get:
$get_ip_short = isset($_POST['ip']) ? trim(preg_replace("/[^0-9a-zA-Z\.\:]/","", $_POST['ip'])) : die();
// via инфа о прокси:
$via = isset($_POST['via']) ? trim(strip_tags($_POST['via'])) : '';
// версия антибота:
$version = isset($_POST['v']) ? (float)trim(preg_replace("/[^0-9\.]/","", $_POST['v'])) : die();
// проверять рекапчу:
$re = isset($_POST['re']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['re'])) : 1;
// проверять хостинг:
$ho = isset($_POST['ho']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['ho'])) : 1;
// id клика:
$cid = isset($_POST['cid']) ? trim(preg_replace("/[^0-9\.]/","", $_POST['cid'])) : die('{"error": "Error 4"}');
// ptr:
$get_ptr = isset($_POST['ptr']) ? trim(preg_replace("/[^0-9a-zA-Z\.\:\-]/","", $_POST['ptr'])) : '';
// ширина монитора:
$w = isset($_POST['w']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['w'])) : die();
// высота монитора:
$h = isset($_POST['h']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['h'])) : die();
// ширина окна браузера:
$cw = isset($_POST['cw']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['cw'])) : die();
// высота окна браузера:
$ch = isset($_POST['ch']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['ch'])) : die();
// colordepth
$co = isset($_POST['co']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['co'])) : die();
// pixeldepth
$pi = isset($_POST['pi']) ? (int)trim(preg_replace("/[^0-9]/","", $_POST['pi'])) : die();
// хост реферера (с ним пришли на сайт):
$ref = isset($_POST['ref']) ? trim(preg_replace("/[^0-9a-zA-Z\.\:\-]/","", $_POST['ref'])) : '';

// таким должен быть правильный хэш:
$antibot = md5($ab_config['email'].$ab_config['pass'].$refhost.$useragent.$accept_lang.$get_ip_short.$re.$ho);

if ($antibot != $h1) die('{"error": "Error 5"}');

$hash = md5($ab_config['pass'].$refhost.$useragent.$get_ip_short); // код для куки
echo '{"cookie": "'.$hash.'", "cid": "'.$cid.'"}';
