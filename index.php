<?php
error_reporting(E_ALL); // E_ALL
ini_set('display_errors', 'on');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/data/errorlog.txt');
header('Content-Type: text/html; charset=UTF-8');
$start_time = microtime(true);

srand(crc32($_SERVER['HTTP_HOST']));
require_once $_SERVER['DOCUMENT_ROOT'].'/data/conf.php';

// всякие входящие переменные:
$time = time();
$uri = trim(strip_tags($_SERVER['REQUEST_URI']));
$host = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^0-9A-Za-z-.:]/","",mb_strtolower(strip_tags(trim($_SERVER['HTTP_HOST'])), 'UTF-8')) : 'localhost';
$referer = isset($_SERVER['HTTP_REFERER']) ? strip_tags(trim($_SERVER['HTTP_REFERER'])) : '';
$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? trim(strip_tags($_SERVER['HTTP_USER_AGENT'])) : '';
$scheme = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? trim(strip_tags($_SERVER['HTTP_X_FORWARDED_PROTO'])) : $_SERVER['REQUEST_SCHEME'];
$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : '';
$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : '';
$lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr(mb_strtolower(trim(preg_replace("/[^a-zA-Z]/","",$_SERVER['HTTP_ACCEPT_LANGUAGE'])), 'UTF-8'), 0, 2, 'utf-8') : '';
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {$ip = trim(strip_tags($_SERVER['HTTP_CF_CONNECTING_IP']));} else {$ip = $_SERVER['REMOTE_ADDR'];}
$human = isset($_COOKIE['hit']) ? trim($_COOKIE['hit']) : ''; // если без рефа должно быть 1
// код страны если не из клауда, то определяем сами:
if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
$country = trim(strip_tags($_SERVER['HTTP_CF_IPCOUNTRY']));
} else {
include($_SERVER['DOCUMENT_ROOT'].'/php/SxGeo.php');
$SxGeo = new SxGeo($_SERVER['DOCUMENT_ROOT'].'/php/SxGeo.dat', SXGEO_MEMORY);
$country = trim($SxGeo->getCountry($ip));
}

// доры могут работать только через клаудфлар:
if ($cloudflare_only == 1) {
if (!isset($_SERVER['HTTP_CF_RAY'])) {
header('HTTP/1.1 404 Not Found');
header('Status: 404 Not Found');
die('404 Not Found');
}
}

// убираем www из хоста:
$host2 = str_replace('www.', '', $host);
if ($host2 != $host) {
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.$scheme.'://'.$host2.$uri);
die();
}

// доры работают только по HTTPS:
if ($scheme != 'https' AND $https_only == 1) {
header('HTTP/1.1 301 Moved Permanently');
header('Location: https://'.$host.$uri);
die();
}

// подключаемся к базе:
$db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/data/'.$db_file); 
$db->busyTimeout(1000);
$db->exec("PRAGMA journal_mode = OFF;");
$db->exec("PRAGMA synchronous = OFF;");

// подключаемся к мемкешеду:
$m = new Memcached();
$m->addServer($memcached_host, $memcached_port);

// заголовки для снижения нагрузки:
if ($notmodified304 == 1) {
header('Expires: ' . gmdate('D, d M Y H:i:s', ($time+864000)) . ' GMT'); // истекает через 10 дней
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $time) . ' GMT'); // дата создания
header('Cache-Control: public, max-age=864000');
if ($if_modified_since != '' OR $if_none_match != '') {
header ('HTTP/1.1 304 Not Modified');
die();
}
}

// ---------------------------------------------------------------------

// функция делает первую букву заглавной:
function upFirstLetter($str, $encoding = 'UTF-8') {
return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).mb_substr($str, 1, null, $encoding);
}

//----------------------------------------------------------------------

// функция размножения контента Боян:
function Bajan($content) {
preg_match_all('#{(.*)}#Ui', $content, $matches);
for($i = 0; $i < sizeof($matches[1]); $i++){
$ns = explode("|", $matches[1][$i]);
$c2 = sizeof($ns);
$rand = rand(0,($c2-1));
$content = str_replace("{".$matches[1][$i]."}", $ns[$rand], $content);
}
return $content;
}
function bojan_start() {
 ob_start();
}
function bojan_end() {
 $out = ob_get_clean(); 
 echo Bajan($out);
}

// ---------------------------------------------------------------------

// псевдорандом с привязкой к полному урлу:
srand(crc32($scheme.'://'.$host.$uri));

// максимальный номер из базы:
$last = @$db->querySingle("SELECT id FROM books ORDER BY id DESC;", true);
if (!isset($last['id'])) die('где кеи блэт?');
$max_num = (int)$last['id'];

// номер текущей строки в базе:
if ($uri == '/' OR $uri == '/index.php') {
// морда:
$thisnum = rand(1, $max_num);
$book = $db->querySingle("SELECT * FROM books WHERE id='".$thisnum."';", true);
// красивый список ссылок на морде:
$num = array();
for ($x=0; $x<$index_links; $x++) {
$num[] = rand(1, $max_num);
}
$index_list = $db->query("SELECT ".$index_links_rows." FROM books WHERE id IN (".implode(",", $num).");");
// конец морды
} else {
// внутряк:
preg_match('!\/(.+?)\.html!siu', $uri, $pageid);
$pageid = @strip_tags(urldecode($pageid[1]));
$pageid = str_replace('_', ' ', $pageid);
$pageid = $db->escapeString($pageid);
$book = $db->querySingle("SELECT * FROM books WHERE title='".$pageid."';", true);
if (!isset($book['id'])) {
header('HTTP/1.1 404 Not Found');
header('Status: 404 Not Found');
die('404 Not Found');
}
}

// добавление домена в линковку живых доменов:
if ($autolink_disable == 0) {
$m->set($memcached_prefix.'s'.mt_rand(1,100), '<a href="'.$scheme.'://'.$host.$uri.'"><div class="uk-card uk-card-default"><div class="uk-card-media-top"><img loading="lazy" src="https://picsum.photos/500/200.webp?random='.rand(1, 500).'" alt="'.$book['title'].'"></div><div class="uk-card-body"><h3>'.$book['title'].'</h3><p>'.mb_strimwidth($book['imgurl'], 0, 100, '...', 'utf-8').'</p></div></div></a>');
}

// внешняя линковка:
$seo = array();
for ($x=0; $x<$ext_links; $x++) {
$url = $m->get($memcached_prefix.'s'.rand(1, 100));
$seo[] = $url;
}

// Link


// внутренняя линковка:
$num = array();
for ($x=0; $x<$int_links; $x++) {
$num[] = rand(1, $max_num);
} 
$menu = array();
$list = $db->query("SELECT title, imgurl FROM books WHERE id IN (".implode(",", $num).");");
while ($echo = $list->fetchArray(SQLITE3_ASSOC)) {
if (mb_strlen($echo['imgurl'], 'utf-8') > 100) {$echo['imgurl'] = mb_strimwidth($echo['imgurl'], 0, 100, '...', 'utf-8');}
$menu[] = '<a href="/'.urlencode(str_replace(' ', '_', $echo['title'])).'.html"><div class="uk-card uk-card-default"><div class="uk-card-media-top"><img loading="lazy" src="https://picsum.photos/400/200.webp?random='.rand(1, 500).'" alt="'.$echo['title'].'"></div><div class="uk-card-body"><h3>'.$echo['title'].'</h3><p>'.$echo['imgurl'].'</p></div></div></a>';
}

$canonical = $scheme.'://'.$host.$uri;

// подгрузка шаблона:
if ($uri == '/') {
require_once $_SERVER['DOCUMENT_ROOT'].'/data/'.$tpl_index;
} else {
require_once $_SERVER['DOCUMENT_ROOT'].'/data/'.$tpl_pages;
}

$exec_time = microtime(true) - $start_time;
$exec_time = round($exec_time, 5);
echo '<!-- Time: '.$exec_time.' Sec. -->';
