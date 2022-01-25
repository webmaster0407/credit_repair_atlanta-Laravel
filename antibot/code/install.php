<?php
// Last update: 2020.08.16
if (!isset($ab_version)) die('stop install');

/*
rules (все правила по белым/черным):
* 
search - строка которую ищем по точному совпадению (уник) a-zA-Z0-9\.\:\-
rule - правило: white или black
comment - просто коммент, чтоб понятнее было
*/
$query = $antibot_db->exec("CREATE TABLE IF NOT EXISTS rules (
search TEXT UNIQUE NOT NULL default '', 
rule TEXT NOT NULL default '', 
comment TEXT NOT NULL default ''
);");

/*
таблица hits:
* 
date - дата вида 20191121
time - время вида 22:12
ip - полный адрес 127.0.0.1
ptr - чтоб не перезапрашивать.
useragent - полностью
uid - хэш из cookie, хэг от ипа и юзерагента, для слежения за юзером
cid - id клика, из микротайм, по идее уник
country - страна (код страны типа: RU)
referer - реферер (возможно обрезать длину)
page - текущая страница полностью
lang - язык полностью
generation - время генерации скрипта антибота
passed - 
* получил заглушку и остался на ней (0 - stop), 
* прошел проверку автоматически в fromcloud.php или ab.php ставить 1 (1 - auto), 
* прошел post кликом, обновлять в процессе на 2 (2 - post), 
* если это хит мимо заглушки (3 - local)
recaptcha - рейтинг score, 
js_w - ширина монитора, 
js_h - высота монитора, 
js_cw - ширина окна браузера, 
js_ch - высота окна браузера, 
js_co - colordepth, 
js_pi - pixeldepth, 
proto - $_SERVER['SERVER_PROTOCOL']
*/
$query = $antibot_db->exec("CREATE TABLE IF NOT EXISTS hits (
date INTEGER NOT NULL default '', 
time TEXT NOT NULL default '', 
ip TEXT NOT NULL default '', 
ptr TEXT NOT NULL default '', 
useragent TEXT NOT NULL default '', 
uid TEXT NOT NULL default '', 
cid TEXT NOT NULL default '', 
country TEXT NOT NULL default '', 
referer TEXT NOT NULL default '', 
page TEXT NOT NULL default '', 
lang TEXT NOT NULL default '', 
generation INTEGER NOT NULL default '0', 
passed INTEGER NOT NULL default '0', 
recaptcha INTEGER NOT NULL default '', 
js_w INTEGER NOT NULL default '', 
js_h INTEGER NOT NULL default '', 
js_cw INTEGER NOT NULL default '', 
js_ch INTEGER NOT NULL default '', 
js_co INTEGER NOT NULL default '', 
js_pi INTEGER NOT NULL default '', 
proto TEXT NOT NULL default ''
);");

/*
таблица fake (лог фейк ботов, заблоченных):
* 
date - дата вида 20191121
time - время вида 22:12
ip - полный адрес 127.0.0.1
ptr - чтоб не перезапрашивать.
useragent - полностью
uid - id юзера
country - страна (код страны типа: RU)
*/
$query = $antibot_db->exec("CREATE TABLE IF NOT EXISTS fake (
date INTEGER NOT NULL default '', 
time TEXT NOT NULL default '', 
ip TEXT NOT NULL default '', 
ptr TEXT NOT NULL default '', 
useragent TEXT NOT NULL default '', 
uid TEXT NOT NULL default '', 
country TEXT NOT NULL default ''
);");

/*
таблица счетчиков (в некотором роде кеш) по датам:
обновлять кроном
* 
date - дата вида 20191223
test - колво попавших на заглушку
auto - колво автоматически прошедших заглушку
click - колво кликнувших на кнопку для прохода заглушки
uusers - колво уников (юзеров по uid) прошедших заглушку
husers - колво хитов (юзеров) прошедших заглушку
whits - колво хитов белых ботов
bbots - колво хитов забаненных (страна, язык, реферер, ptr)
fakes - колво хитов фейк ботов
google - кол-во хитов гугл бота
yandex - кол-во хитов яндекс бота
mailru - кол-во хитов мейл бота
bing - кол-во хитов бинг бота
*/
$query = $antibot_db->exec("CREATE TABLE IF NOT EXISTS counters (
date INTEGER UNIQUE NOT NULL default '', 
test INTEGER NOT NULL default '0', 
auto INTEGER NOT NULL default '0', 
click INTEGER NOT NULL default '0', 
uusers INTEGER NOT NULL default '0', 
husers INTEGER NOT NULL default '0', 
whits INTEGER NOT NULL default '0', 
bbots INTEGER NOT NULL default '0', 
fakes INTEGER NOT NULL default '0', 
google INTEGER NOT NULL default '0', 
yandex INTEGER NOT NULL default '0', 
mailru INTEGER NOT NULL default '0', 
bing INTEGER NOT NULL default '0'
);");

echo 'The database is installed.
<script>location.reload();</script>';
