<?php
// Version: 7.010
// Author: Mik Foxi admin@mikfoxi.com
// License: GNU GPL v3 - https://www.gnu.org/licenses/gpl-3.0.en.html
// Website: https://antibot.cloud/
// Last update: 2020.08.21

$ab_version = '7.010';
$ab_se = array();
$ab_proxy = array();
$ab_config['header_error_code'] = 200;
$ab_config['custom_error_page'] = 0;
$ab_config['disable_editing'] = 1;
$ab_config['check_ref_traf'] = 0;
$ab_config['allow_ref_only'] = array();
$ab_config['block_stop_cookie'] = 0;
$ab_config['recaptcha_key'] = '6LdOLMAUAAAAADWpsyq-fXIrXnG8eTU0judcHJiF';
$ab_config['extended_bot_stat'] = 0;

$ab_start_time = microtime(true);

require_once(__DIR__.'/../data/conf.php');
require_once(__DIR__.'/func.php');

// чтобы эти настройки не мешали доступу в админку:
if(defined('ANTIBOT_ADMIN')) {
$ab_config['stop_noreferer'] = 0;
$ab_config['antibot_log_users'] = 0;
$ab_config['check_ref_traf'] = 0;
$ab_config['block_stop_cookie'] = 0;
}

// подключаемся к мемкешеду:
if ($ab_config['memcached_counter'] == 1) {
$ab_memcached = new Memcached();
$ab_memcached->addServer($ab_config['memcached_host'], $ab_config['memcached_port']);
}

$ab_config['time'] = time();
$ab_config['date'] = date('Y.m.d', $ab_config['time']);
$ab_config['host'] = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^0-9a-z-.:]/","", $_SERVER['HTTP_HOST']) : die('Host Error');
$ab_config['scheme'] = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? trim(strip_tags($_SERVER['HTTP_X_FORWARDED_PROTO'])) : trim(strip_tags($_SERVER['REQUEST_SCHEME']));
$ab_config['useragent'] = isset($_SERVER['HTTP_USER_AGENT']) ? trim(strip_tags($_SERVER['HTTP_USER_AGENT'])) : '';
$ab_config['uri'] = trim(strip_tags($_SERVER['REQUEST_URI']));
$ab_config['ip'] = trim(strip_tags($_SERVER['REMOTE_ADDR']));
$ab_config['http_via'] = isset($_SERVER['HTTP_VIA']) ? trim(strip_tags($_SERVER['HTTP_VIA'])) : '';
$ab_config['referer'] = isset($_SERVER['HTTP_REFERER']) ? trim(strip_tags($_SERVER['HTTP_REFERER'])) : '';
$ab_config['refhost'] = preg_replace("/[^0-9a-z-.:]/","", parse_url($ab_config['referer'], PHP_URL_HOST));
$ab_config['accept_lang'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? trim(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE'])) : '';
$ab_config['lang'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr(mb_strtolower(trim(preg_replace("/[^a-zA-Z]/","",$_SERVER['HTTP_ACCEPT_LANGUAGE'])), 'UTF-8'), 0, 2, 'utf-8') : ''; // 2 первых символа
if ($ab_config['useragent'] == '') die('User Agent Error'); // пустой юзерагент.
//if ($ab_config['timer'] < 1) {$ab_config['timer'] = 1;}
$ab_config['protocol'] = (isset($_SERVER['SERVER_PROTOCOL']) ? trim(strip_tags($_SERVER['SERVER_PROTOCOL'])) : 'HTTP/1.0');

// перевод на язык посетителя:
if (file_exists(__DIR__.'/../lang/tpl/'.$ab_config['lang'].'.php')) {
require_once(__DIR__.'/../lang/tpl/'.$ab_config['lang'].'.php');
}

// уник id юзера:
$ab_config['uid'] = isset($_COOKIE['antibot_uid']) ? trim(preg_replace("/[^0-9a-z]/","",$_COOKIE['antibot_uid'])) : '';
// уник id юзера в cookie:
if ($ab_config['uid'] == '') {
$ab_config['uid'] = md5($ab_config['ip'].$ab_config['useragent'].$ab_config['accept_lang']);
setcookie('antibot_uid', $ab_config['uid'], time()+31536000, '/'); // на год
}

$ab_config['cid'] = microtime(true); // уникальный id клика

// адрес скрипта проверки:
if ($ab_config['check_url'] == '') {
$ab_config['check_url'] = 'https://cloud.antibot.cloud/antibot7.php';
$ab_config['check_url2'] = 'https://alt.antibot.cloud/antibot7.php';
} else {
$ab_config['check_url2'] = $ab_config['check_url'];
}

// запись реферер в куки, чтоб за антиботом о нем можно было знать:
if (!isset($_COOKIE['antibot_referer'])) {
setcookie('antibot_referer', $ab_config['referer'], $ab_config['time']+5184000, '/'); // на 2 мес
}

// проверка на использование прокси:
if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
$ip=ip2long($_SERVER['REMOTE_ADDR']);
foreach ($ab_proxy as $proxy_mask => $proxy_attr) {
$net = $proxy_mask;
list($net,$mask)=explode('/',$net);
$net=ip2long($net);
$mask=pow(2,32-$mask)-1;
$net=$net&~$mask;
if (!(($ip^$net)&~$mask)) {$ab_config['ip'] = $_SERVER[$proxy_attr]; break;}
}
}

// проверка валидности ip:
if (filter_var($ab_config['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
$ab_config['ipv'] = 4;
$ab_config_ip_array = explode('.', $ab_config['ip']);
$ab_config['ip_short'] = $ab_config_ip_array[0].'.'.$ab_config_ip_array[1].'.'.$ab_config_ip_array[2];
} elseif (filter_var($ab_config['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
$ab_config['ip'] = abExpand($ab_config['ip']); // на всякий случай
$ab_config['ipv'] = 6;
$ab_config_ip_array = explode(':', $ab_config['ip']);
$ab_config['ip_short'] = $ab_config_ip_array[0].':'.$ab_config_ip_array[1].':'.$ab_config_ip_array[2].':'.$ab_config_ip_array[3];
} else {
die('Bad IP');
}

// коннект к базе:
$antibot_db = new SQLite3(__DIR__.'/../data/sqlite.db'); 
$antibot_db->busyTimeout(5000);
$antibot_db->exec("PRAGMA journal_mode = WAL;");

// для начала всех считаем людьми:
$ab_config['whitebot'] = 0;

// получаем IP полный и укороченный из базы:
$antibot_test_ip = @$antibot_db->querySingle("SELECT rule FROM rules WHERE search='".$ab_config['ip']."' OR search='".$ab_config['ip_short']."';", true);

// установка базы данных, если ее нету:
if ($antibot_db->lastErrorMsg() == 'no such table: rules') {
require_once(__DIR__.'/install.php');
die();
}

// коды ответа для заблокированных пользователей:
$ab_config['error_headers'] = array(
200 => '200 OK', 
400 => '400 Bad Request', 
403 => '403 Forbidden', 
404 => '404 Not Found', 
410 => '410 Gone', 
451 => '451 Unavailable For Legal Reasons', 
500 => '500 Internal Server Error', 
502 => '502 Bad Gateway', 
503 => '503 Service Unavailable', 
504 => '504 Gateway Time-out'
);
if (!isset($ab_config['error_headers'][$ab_config['header_error_code']])) {
$ab_config['header_error_code'] = 200;
}

// запрет доступа тем, кто уже попадал под правила блокировки:
if ($ab_config['block_stop_cookie'] == 1) {
if (isset($_COOKIE['stop'])) {
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}

// самый белый бот, если нашли, то дальше уже не проверяем:
if (@$antibot_test_ip['rule'] == 'white') {
$ab_config['whitebot'] = 1;
} elseif (@$antibot_test_ip['rule'] == 'black') {
// блочим доступ из блек листа:
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
} else {
// ip в базе нету, проверяем дальше...
}

if ($ab_config['whitebot'] == 0) {
// проверяем юзерагент на принадлежность к белым ботам по массиву из конфига:
foreach ($ab_se as $ab_line => $ab_sign) {
// если нашли совпадение в имени юзерагента:
if (stripos($ab_config['useragent'], $ab_line, 0) !== false) {
if (TestWhiteBot($ab_config['ip'], $ab_sign) == 1) {
// если это в реале по ptr белый бот:
if ($ab_line != '.') {
// сохраняем ip в белый список только тем у кого полноценный идентифицируемый ptr:
if ($ab_config['short_mask'] != 1) {$ab_config['ip_short'] = $ab_config['ip'];}
$ab_config['useragent'] = $antibot_db->escapeString($ab_config['useragent']);
$add = @$antibot_db->exec("INSERT INTO rules (search, rule, comment) VALUES ('".$ab_config['ip_short']."', 'white', '".$ab_config['useragent']." (".$ab_config['ip'].")');");
}
$ab_config['whitebot'] = 1; break;
} else {
// фейковый бот:
$ab_config['whitebot'] = 0;
if ($ab_config['antibot_log_fakes'] == 1) {
$ab_config['ptr'] = $antibot_db->escapeString(gethostbyaddr($ab_config['ip']));
$ab_config['useragent'] = $antibot_db->escapeString($ab_config['useragent']);
// код страны если не из клауда, то определяем сами (если ipv4):
if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
$ab_config['country'] = trim(preg_replace("/[^A-Z]/","", $_SERVER['HTTP_CF_IPCOUNTRY']));
} elseif ($ab_config['ipv'] == 4) {
if(!defined('SXGEO_FILE')) {
include(__DIR__.'/SxGeo.php');
}
$SxGeo = new SxGeo(__DIR__.'/SxGeo.dat', SXGEO_MEMORY);
$ab_config['country'] = trim($SxGeo->getCountry($ab_config['ip']));
} else {
$ab_config['country'] = '';
}
$add = @$antibot_db->exec("INSERT INTO fake (date, time, ip, ptr, useragent, uid, country) VALUES ('".date("Ymd", $ab_config['time'])."', '".date("H:i:s", $ab_config['time'])."', '".$ab_config['ip']."', '".$ab_config['ptr']."', '".$ab_config['useragent']."', '".$ab_config['uid']."', '".$ab_config['country']."');");
}
// счетчик фейк ботов:
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'fakes_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'fakes_'.date('Ymd', $ab_config['time']), 1);}
}
if ($ab_config['stop_fake'] == 1) {
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}
break;
}
}
}

// дальше проверяем только людей:
if ($ab_config['whitebot'] == 0) {
	
// хэш для куки таким должен быть:
$ab_config['antibot_ok'] = md5($ab_config['pass'].$ab_config['host'].$ab_config['useragent'].$ab_config['ip_short']);

// получаем куки юзера (привязка к подсети ип):
$ab_cookiename = 'antibot_'.md5($ab_config['salt'].$ab_config['host'].$ab_config['ip_short']);
$ab_config['antibot'] = isset($_COOKIE[$ab_cookiename]) ? trim(strip_tags($_COOKIE[$ab_cookiename])) : '';

// проверка пост запросом:
if(isset($_POST['submit']) AND isset($_POST['antibot']) AND $ab_config['input_button'] == 0) {
setcookie('lastcid', 0, $ab_config['time']-100, '/');
$_POST['time'] = isset($_POST['time']) ? (int)trim(strip_tags($_POST['time'])) : 0;
$_POST['antibot'] = isset($_POST['antibot']) ? trim(strip_tags($_POST['antibot'])) : 0;
$_POST['cid'] = isset($_POST['cid']) ? trim(preg_replace("/[^0-9\.]/","", $_POST['cid'])) : die('cid');
if ($ab_config['time'] - $_POST['time'] < 600 AND md5($ab_config['salt'].$_POST['time'].$ab_config['ip'].$ab_config['useragent']) == $_POST['antibot']) {
setcookie($ab_cookiename, $ab_config['antibot_ok'], $ab_config['time']+86400, '/', $ab_config['host']);
$ab_config['antibot'] = $ab_config['antibot_ok'];
// обновление лога о проходе заглушки:
if ($ab_config['antibot_log_tests'] == 1) {
$update = $antibot_db->exec("UPDATE hits SET passed='2' WHERE cid='".$_POST['cid']."';");
}
// счетчик прошедших заглушку по клику:
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'click_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'click_'.date('Ymd', $ab_config['time']), 1);}
}
}
}

// перенос статистики из memcached в базу:
if ($ab_config['memcached_counter'] == 1) {
$cron_update_time = $ab_memcached->get($ab_config['memcached_prefix'].'update') + 0;
if ($ab_config['time'] - $cron_update_time > 600) {
$ab_memcached->set($ab_config['memcached_prefix'].'update', $ab_config['time']); 
require_once(__DIR__.'/cron.php');
}
}

// отдаем юзеру заглушку для проверки:
if ($ab_config['antibot_ok'] != $ab_config['antibot']) {
header('Content-Type: text/html; charset=UTF-8');
header('X-Powered-CMS: AntiBot.Cloud (See: https://antibot.cloud/)');
//header('X-Robots-Tag: noindex');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');

// запрет доступа по белому списку рефереров:
if ($ab_config['check_ref_traf'] == 1) {
if(!preg_match("/".implode('|',$ab_config['allow_ref_only'])."/",$ab_config['refhost'])){
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}

// запрет доступа с пустым реферером:
if ($ab_config['stop_noreferer'] == 1) {
if ($ab_config['refhost'] == '') {
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);}
}
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}

// запрет доступа с пустым языком:
if ($ab_config['stop_nolang'] == 1) {
if ($ab_config['accept_lang'] == '') {
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);}
}
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}

// проверка HTTP/2.0 если включена:
if ($ab_config['http2only'] == 1 AND $_SERVER['SERVER_PROTOCOL'] != 'HTTP/2.0') {
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);}
}
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}

// полная PTR запись:
$ab_config['ptr'] = trim(strip_tags(gethostbyaddr($ab_config['ip'])));

// PTR 2 и 3 уровня если есть:
$ab_config['ptr_arr'] = explode('.', $ab_config['ptr']);
$ab_config['ptr_arr'] = array_reverse($ab_config['ptr_arr'], false);
$ab_config['search'] = array();
if (isset($ab_config['ptr_arr'][1])) {
$ab_config['search'][] = $antibot_db->escapeString($ab_config['ptr_arr'][1].'.'.$ab_config['ptr_arr'][0]);
}
if (isset($ab_config['ptr_arr'][2])) {
$ab_config['search'][] = $antibot_db->escapeString($ab_config['ptr_arr'][2].'.'.$ab_config['ptr_arr'][1].'.'.$ab_config['ptr_arr'][0]);
}
// рефхост для поиска, если есть:
if ($ab_config['refhost'] != '') {
$ab_config['search'][] = $ab_config['refhost'];
}

// код страны если не из клауда, то определяем сами (если ipv4):
if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
$ab_config['country'] = trim(preg_replace("/[^A-Z]/","", $_SERVER['HTTP_CF_IPCOUNTRY']));
} elseif ($ab_config['ipv'] == 4) {
if(!defined('SXGEO_FILE')) {
include(__DIR__.'/SxGeo.php');
}
$SxGeo = new SxGeo(__DIR__.'/SxGeo.dat', SXGEO_MEMORY);

$ab_config['country'] = trim($SxGeo->getCountry($ab_config['ip']));
} else {
$ab_config['country'] = 'XX';
}
if ($ab_config['country'] == '') {$ab_config['country'] = 'XX';}

$ab_config['search'][] = $ab_config['country'];
$ab_config['search'][] = $ab_config['lang'];

// проверка и блокировка по стране, языку, рефереру, PTR:
$list = $antibot_db->query("SELECT rule FROM rules WHERE search='".implode("' OR search='", $ab_config['search'])."';");
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
if ($echo['rule'] == 'black') {
// счетчик забаненных (страна, язык, реферер, ptr):
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'bbots_'.date('Ymd', $ab_config['time']), 1);}
}
header($ab_config['protocol'].' '.$ab_config['error_headers'][$ab_config['header_error_code']]);
header('Status: '.$ab_config['error_headers'][$ab_config['header_error_code']]);
setcookie('stop', 1, $ab_config['time']+864000, '/');
if ($ab_config['custom_error_page'] == 1) {
require_once(__DIR__.'/../data/error.txt');
die();
} else {
die($ab_config['error_headers'][$ab_config['header_error_code']]);
}
}
}

// хитробот фейсбука:
if (isset($_GET['fbclid']) AND $ab_config['country'] == 'IE') die('404');

setcookie('antibot_country', $ab_config['country'], $ab_config['time']+86400, '/', $ab_config['host']);
setcookie('antibot_lang', $ab_config['lang'], $ab_config['time']+86400, '/', $ab_config['host']);
setcookie('antibot_ptr', $ab_config['ptr'], $ab_config['time']+86400, '/', $ab_config['host']);

// счетчик показов заглушки:
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'test_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'test_'.date('Ymd', $ab_config['time']), 1);}
}

// показываем заглушку:
require_once(__DIR__.'/../data/tpl.txt');
if ($ab_config['antibot_log_tests'] == 1) {
//запись в лог попавших на заглушку:
$ab_config['ptr'] = $antibot_db->escapeString($ab_config['ptr']);
$ab_config['useragent'] = $antibot_db->escapeString($ab_config['useragent']);
$ab_config['referer'] = $antibot_db->escapeString($ab_config['referer']);
$ab_config['accept_lang'] = $antibot_db->escapeString($ab_config['accept_lang']);
$ab_config['page'] = $antibot_db->escapeString($ab_config['scheme'].'://'.$ab_config['host'].$ab_config['uri']);
$ab_exec_time = microtime(true) - $ab_start_time;
$add = @$antibot_db->exec("INSERT INTO hits (date, time, ip, ptr, useragent, uid, cid, country, referer, page, lang, generation, passed) VALUES ('".date("Ymd", $ab_config['time'])."', '".date("H:i:s", $ab_config['time'])."', '".$ab_config['ip']."', '".$ab_config['ptr']."', '".$ab_config['useragent']."', '".$ab_config['uid']."', '".$ab_config['cid']."', '".$ab_config['country']."', '".$ab_config['referer']."', '".$ab_config['page']."', '".$ab_config['accept_lang']."', '".$ab_exec_time."', '0');");
}
$ab_exec_time = microtime(true) - $ab_start_time;
$ab_exec_time = round($ab_exec_time, 5);
echo '<!-- Time: '.$ab_exec_time.' Sec. -->';
// конец вывод заглушки.
die();
}

if ($ab_config['antibot_log_users'] == 1) {
//запись в лог имеющих разрешающие cookie:
$ab_config['country'] = isset($_COOKIE['antibot_country']) ? trim(preg_replace("/[^A-Z]/","",$_COOKIE['antibot_country'])) : '';
$ab_config['ptr'] = isset($_COOKIE['antibot_ptr']) ? trim(preg_replace("/[^a-zA-Z0-9\-\.\_]/","",$_COOKIE['antibot_ptr'])) : '';
$ab_config['ptr'] = $antibot_db->escapeString($ab_config['ptr']);
$ab_config['useragent'] = $antibot_db->escapeString($ab_config['useragent']);
$ab_config['referer'] = $antibot_db->escapeString($ab_config['referer']);
$ab_config['accept_lang'] = $antibot_db->escapeString($ab_config['accept_lang']);
$ab_config['page'] = $antibot_db->escapeString($ab_config['scheme'].'://'.$ab_config['host'].$ab_config['uri']);
$ab_exec_time = microtime(true) - $ab_start_time;
$add = @$antibot_db->exec("INSERT INTO hits (date, time, ip, ptr, useragent, uid, cid, country, referer, page, lang, generation, passed) VALUES ('".date("Ymd", $ab_config['time'])."', '".date("H:i:s", $ab_config['time'])."', '".$ab_config['ip']."', '".$ab_config['ptr']."', '".$ab_config['useragent']."', '".$ab_config['uid']."', '".$ab_config['cid']."', '".$ab_config['country']."', '".$ab_config['referer']."', '".$ab_config['page']."', '".$ab_config['accept_lang']."', '".$ab_exec_time."', '3');");
}
}

if ($ab_config['whitebot'] == 1) {
// счетчик белых ботов:
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'whits_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'whits_'.date('Ymd', $ab_config['time']), 1);}
// расширенный счетчик поисковых ботов:
if ($ab_config['extended_bot_stat'] == 1) {
if (stripos($ab_config['useragent'], 'Googlebot', 0) !== false) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'google_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'google_'.date('Ymd', $ab_config['time']), 1);}
} elseif (stripos($ab_config['useragent'], 'yandex.com', 0) !== false) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'yandex_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'yandex_'.date('Ymd', $ab_config['time']), 1);}
} elseif (stripos($ab_config['useragent'], 'Mail.RU_Bot', 0) !== false) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'mailru_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'mailru_'.date('Ymd', $ab_config['time']), 1);}
} elseif (stripos($ab_config['useragent'], 'bingbot', 0) !== false) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'bing_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'bing_'.date('Ymd', $ab_config['time']), 1);}
} else {
// ничего не записываем
}
}
// конец расширенного счетчика.
}
} else {
// счетчик юзеров прошедших заглушку:
if ($ab_config['memcached_counter'] == 1) {
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'husers_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'husers_'.date('Ymd', $ab_config['time']), 1);}
}
// счетчик уник юзеров прошедших заглушку:
if (!isset($_COOKIE['antibot_unique_'.date('Ymd', $ab_config['time'])]) AND $ab_config['memcached_counter'] == 1) {
setcookie('antibot_unique_'.date('Ymd', $ab_config['time']), '1', $ab_config['time']+86400, '/', $ab_config['host']);
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'uusers_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'uusers_'.date('Ymd', $ab_config['time']), 1);}
}
}

// обновление счетчика автоматических прохождений:
if (isset($_COOKIE['lastcid'])) {
$lastcid = trim(preg_replace("/[^0-9\.]/","", $_COOKIE['lastcid']));
setcookie('lastcid', 0, $ab_config['time']-100, '/');
// обновление лога о проходе заглушки:
if ($ab_config['antibot_log_tests'] == 1) {
$update = @$antibot_db->exec("UPDATE hits SET passed='1' WHERE cid='".$lastcid."';");
}
if ($ab_config['memcached_counter'] == 1) {
// счетчик автоматически прошедших:
$ab_mem = $ab_memcached->increment($ab_config['memcached_prefix'].'auto_'.date('Ymd', $ab_config['time']), 1);
if (!$ab_mem) {$ab_memcached->set($ab_config['memcached_prefix'].'auto_'.date('Ymd', $ab_config['time']), 1);}
}
}
