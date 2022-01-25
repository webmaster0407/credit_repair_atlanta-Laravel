<?php
// Last update date: 2020.08.19

// most of the settings that are listed here are recommended settings.
// большинство настроек, которые тут указаны - это рекомендуемые настройки.

error_reporting(E_ALL); // 0 or 1
ini_set('display_errors', 'on'); // off or on
ini_set('error_log', 'data/errorlog.txt');

// email to access the admin panel (if cloud version, write email from antibot.cloud).
// емейл для доступа в админку (если cloud версия, то email от antibot.cloud).
$ab_config['email'] = 'pacific';

// password for access to the admin panel (if cloud version, write password from antibot.cloud).
// пароль для доступа в админку (если cloud версия, то пароль от antibot.cloud).
$ab_config['pass'] = 'pacific777#';

// salt, change to reset cookie to visitors.
// соль, изменить для сброса cookie посетителям.
$ab_config['salt'] = 'xyz';

// for connecting cloud checking - this value must be empty: $ab_config['check_url'] = '';
// для подключения облачной проверки - это значение должно быть пустым: $ab_config['check_url'] = '';
$ab_config['check_url'] = '/antibot/ab.php'; // /antibot/ab.php - local checking (free)

// timeout when checking before the button appears (in seconds, 5 is optimal).
// время ожидания при проверке, до появления кнопки (в секундах, 5 оптимально).
$ab_config['timer'] = 2;

// deny access to visitors with an empty referrer on the checking page.
// 0 - do not deny access, 1 - deny access.
// запретить на странице проверки доступ посетителям с пустым реферером.
// 0 - не запрещать доступ, 1 - запретить доступ.
$ab_config['stop_noreferer'] = 0;

// deny access to visitors with an empty HTTP_ACCEPT_LANGUAGE on the checking page.
// 0 - do not deny access, 1 - deny access.
// запретить на странице проверки доступ посетителям с пустым HTTP_ACCEPT_LANGUAGE.
// 0 - не запрещать доступ, 1 - запретить доступ.
$ab_config['stop_nolang'] = 1;

// disable the ability to access the website at the click of a button (if not passed automatic checking).
// 0 - do not disable the button, 1 - disable the button.
// отключить возможность зайти на сайт по нажатию кнопки (если не прошел автоматическую проверку).
// 0 - не отключать кнопку, 1 - отключить кнопку.
$ab_config['input_button'] = 0;

// enable reCAPTCHA v3 checking (for cloud checking). 0 - disabled, 1 - enabled.
// visitors from China will not pass, google.com they have blocked.
// включить reCAPTCHA v3 фильтр (при облачной проверке). 0 - выключить, 1 - включить.
// посетители из Китая не пройдут, google.com у них не доступен.
$ab_config['re_check'] = 0;

// enable Hosting checking (for cloud checking). 0 - disabled, 1 - enabled.
// blocking automatic access of users with ip addresses belonging to hosting companies.
// включить Hosting фильтр (при облачной проверке). 0 - выключить, 1 - включить.
// блокировка автоматического прохода пользователей с ip, принадлежащих хостингам.
$ab_config['ho_check'] = 0;

// if the website runs on https with http/2.0 support.
// 1 - only allow users who support http2.
// 0 - allow all verified cookies.
// если сайт работает на https c поддержкой http/2.0
// 1 - пускать только юзеров, поддерживающих http2.
// 0 - пускать всех прошедших проверку cookie.
$ab_config['http2only'] = 0;

// save the good bots to the white list ip by mask /24 for ipv4 and by mask /64 for ipv6.
// 1 - shortened record (recommended), 0 - full ip.
// сохранять в белый список ip хороших ботов по маске /24 для ipv4 и по маске /64 для ipv6.
// 1 - сокращенная запись (рекомендуется), 0 - полный ip.
$ab_config['short_mask'] = 1;

// if the visitor is defined as a fake bot (with a user agent like a good bot):
// 1 - stop script execution (recommended)
// 0 - allow checking as a person.
// если зашел фейкбот (с юзерагентом как у хорошего бота):
// 1 - остановить выполнение скрипта (рекомендуется)
// 0 - разрешить пройти проверку как человеку.
$ab_config['stop_fake'] = 1; 

// ---------------------------------------------------------------------

// LOGS (1 - enable, 0 - disable).
// ЛОГИ (1 - включить лог, 0 - не вести лог).

// log of visitors to the checking page.
// лог посетителей попавших на страницу проверки.
$ab_config['antibot_log_tests'] = 1;

// log of visitors who passed the checking page.
// лог посетителей прошедших страницу проверки.
$ab_config['antibot_log_users'] = 0;

// fake bot log (with a user agent like a good bot, but with incorrect PTR).
// лог фейковых ботов (с юзерагентом как у хорошего бота, но с не правильным PTR).
$ab_config['antibot_log_fakes'] = 1;

// ---------------------------------------------------------------------

// statistics counters in memcached. 1 - enable, 0 - disable.
// счетчики статистики в мемкешед. 1 - включить, 0 - отключить.
$ab_config['memcached_counter'] = 1;

$ab_config['memcached_host'] = '/var/run/memcached/memcached.sock';
$ab_config['memcached_port'] = 0;

// prefix for the data in the memcached (must be unique for each AntiBot software on the server.).
// префикс для данных в мемкешеде (должен быть уникальным для каждого скрипта антибота на сервере).
$ab_config['memcached_prefix'] = 'antibot_';

// расширенная статистика по ботам: yandex, google, mailru, bing. 1 - включить, 0 - отключить:
// extended statistics on search bots: yandex, google, mailru, bing. 1 - enable, 0 - disable.
$ab_config['extended_bot_stat'] = 1;

// ---------------------------------------------------------------------

// server response code for users blocked in the rules. available options:
// код ответа сервера для заблокированных в правилах пользователей. доступные варианты:
// Only: 200, 400, 403, 404, 410, 451, 500, 502, 503, 504.
// See: https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
$ab_config['header_error_code'] = 200;

// content shown to blocked users:
// 0 - system message depending on the error code.
// 1 - your content from antibot/data/error.txt
// контент показываемый заблокированным пользователям:
// 0 - системное сообщение в зависимости от кода.
// 1 - свой контент из antibot/data/error.txt
$ab_config['custom_error_page'] = 0;

// разрешать доступ только посетителям с указанных рефереров. проверяется только на заглушке.
// 1 - пускать только по белому списку рефереров.
// 0 - не проверять реферер и пускать на заглушку всех.
// с реферером не из белого списка посетитель будет видеть страницу ошибки.
$ab_config['check_ref_traf'] = 0;

// эти слова искать в хост реферера для разрешения доступа к заглушке антибота:
$ab_config['allow_ref_only'] = array('yandex', 'google', 'bing', 'mail.ru');

// если посетитель попал под какое либо из правил блокировки и получил страницу блокировки, 
// то также ему устанавливается cookie с именем stop на 10 дней.
// 1 - блокировать этих посетителей в дальнейшем, даже если они больше не подпадают под правила блокировки.
// 0 - не блокировать.
$ab_config['block_stop_cookie'] = 0;

// ---------------------------------------------------------------------

// List of good bots in the format: signature from User-Agent => array of PTR records:
// if the PTR record is empty or uninformative, then specify array('.');
// then all bots with this user agent will be skipped as good bots,
// but ip will not be added to the base of good bots.
// if the bot comes from a small number of subnets, then you can specify a part of the ip address.

// Список белых ботов в формате: сигнатура (признак) из User-Agent => массив PTR записей:
// если PTR запись пустая или неинформативная, то указывать array('.');
// тогда все боты с этим юзерагентом будут пропускаться как белые боты,
// но ip в базу белых ботов добавляться не будут.
// если бот ходит из малого количества подсетей, то можно указать часть ip адреса.

$ab_se['Googlebot'] = array('.googlebot.com'); // GoogleBot (main indexer)
//$ab_se['yandex.com'] = array('yandex.ru', 'yandex.net', 'yandex.com'); // All Yandex bots
//$ab_se['Mail.RU_Bot'] = array('mail.ru', 'smailru.net'); // All Bots Mail.RU Indexers
$ab_se['bingbot'] = array('search.msn.com'); // Bing.com indexer
//$ab_se['msnbot'] = array('search.msn.com'); // Additional Indexer Bing.com
//$ab_se['Google-Site-Verification'] = array('googlebot.com', 'google.com'); // Check for Google Search Console
//$ab_se['vkShare'] = array('.vk.com', '.vkontakte.ru', '.go.mail.ru', '.userapi.ru'); // vkontakte
//$ab_se['facebookexternalhit'] = array('.fbsv.net', '66.220.149.', '31.13.', '2a03:2880:'); // Facebook
//$ab_se['OdklBot'] = array('.odnoklassniki.ru'); // Однокласники
//$ab_se['MailRuConnect'] = array('.smailru.net'); // Мой мир (mail.ru)
//$ab_se['TelegramBot'] = array('149.154.161'); // Telegram
//$ab_se['Twitterbot'] = array('.twttr.com', '199.16.15'); // Twitter
//$ab_se['googleweblight'] = array('google.com'); // 
//$ab_se['BingPreview'] = array('search.msn.com'); // Check Bing Mobile Page Adaptation
//$ab_se['uptimerobot'] = array('uptimerobot.com');
//$ab_se['pingdom'] = array('pingdom.com');
//$ab_se['HostTracker'] = array('.'); //
//$ab_se['Yahoo! Slurp'] = array('.yahoo.net'); // Yahoo Bots
//$ab_se['SeznamBot'] = array('.seznam.cz'); // seznam.cz
//$ab_se['Pinterestbot'] = array('.pinterest.com'); // 
//$ab_se['Mediapartners'] = array('googlebot.com', 'google.com'); // AdSense bot
//$ab_se['AdsBot-Google'] = array('google.com'); // Adwords bot
//$ab_se['Google-Adwords'] = array('google.com'); // Adwords bot (Google-Adwords-Instant и Google-AdWords-Express
//$ab_se['Google-Ads'] = array('google.com'); // Adwords bot (Google-Ads-Creatives-Assistant)
//$ab_se['Google Favicon'] = array('google.com');
//$ab_se['FeedFetcher-Google'] = array('google.com'); // google news

// ---------------------------------------------------------------------

// If the website (php) is behind the proxy (apache for nginx or cloudflare, etc.), 
// specify the subnet ip of the proxy servers and the value of the $_SERVER 
// variable from which to take the real visitor ip. Only ipv4 is supported.

// Если сайт (php) находится за прокси (apache за nginx или cloudflare и т.п.)
// укажите подсеть ip прокси серверов и значение $_SERVER переменной из которой 
// брать реальный ip посетителя. поддерживаются только ipv4.

// CloudFlare:
$ab_proxy['173.245.48.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.21.244.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.22.200.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.31.4.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['141.101.64.0/18'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['108.162.192.0/18'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['190.93.240.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['188.114.96.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['197.234.240.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['198.41.128.0/17'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['162.158.0.0/15'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['104.16.0.0/12'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['172.64.0.0/13'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['131.0.72.0/22'] = 'HTTP_CF_CONNECTING_IP';

// ---------------------------------------------------------------------

// Security setting!
// for files: conf.php, counter.txt, tpl.txt, error.txt
// disable file editing in admin panel. 1 - disable editing, 0 - allow editing.
// запретить редактировать файлы через админку. 1 - запретить, 0 - разрешить.
$ab_config['disable_editing'] = 0;