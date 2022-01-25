<?php
// Last update date: 2020.08.21
// cron для переноса статистики из мемкешед в базу
if (!isset($ab_version)) die('stop cron');

$ab_time_cron = $ab_config['time'];
for ($x = 0; $x < 2; $x++) {
$date = date('Ymd', $ab_time_cron);

$test = $ab_memcached->get($ab_config['memcached_prefix'].'test_'.$date) + 0;
$auto = $ab_memcached->get($ab_config['memcached_prefix'].'auto_'.$date) + 0;
$click = $ab_memcached->get($ab_config['memcached_prefix'].'click_'.$date) + 0;
$uusers = $ab_memcached->get($ab_config['memcached_prefix'].'uusers_'.$date) + 0;
$husers = $ab_memcached->get($ab_config['memcached_prefix'].'husers_'.$date) + 0;
$whits = $ab_memcached->get($ab_config['memcached_prefix'].'whits_'.$date) + 0;
$bbots = $ab_memcached->get($ab_config['memcached_prefix'].'bbots_'.$date) + 0;
$fakes = $ab_memcached->get($ab_config['memcached_prefix'].'fakes_'.$date) + 0;

$google = $ab_memcached->get($ab_config['memcached_prefix'].'google_'.$date) + 0;
$yandex = $ab_memcached->get($ab_config['memcached_prefix'].'yandex_'.$date) + 0;
$mailru = $ab_memcached->get($ab_config['memcached_prefix'].'mailru_'.$date) + 0;
$bing = $ab_memcached->get($ab_config['memcached_prefix'].'bing_'.$date) + 0;

// очищаем счет в мемкешеде:
$ab_memcached->set($ab_config['memcached_prefix'].'test_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'auto_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'click_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'uusers_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'husers_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'whits_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'bbots_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'fakes_'.$date, 0); 

$ab_memcached->set($ab_config['memcached_prefix'].'google_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'yandex_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'mailru_'.$date, 0); 
$ab_memcached->set($ab_config['memcached_prefix'].'bing_'.$date, 0); 

$update = @$antibot_db->exec("UPDATE counters SET test = test + ".$test.", auto = auto + ".$auto.", click = click + ".$click.", uusers = uusers + ".$uusers.", husers = husers + ".$husers.", whits = whits + ".$whits.", bbots = bbots + ".$bbots.", fakes = fakes + ".$fakes.", google = google + ".$google.", yandex = yandex + ".$yandex.", mailru = mailru + ".$mailru.", bing = bing + ".$bing." WHERE date = '".$date."';");

$lastErrorMsg = $antibot_db->lastErrorMsg();
if ($lastErrorMsg == 'no such column: google' OR $lastErrorMsg == 'no such column: yandex' OR $lastErrorMsg == 'no such column: mailru' OR $lastErrorMsg == 'no such column: bing') {
$add = @$antibot_db->exec("ALTER TABLE counters ADD google INTEGER NOT NULL default '0';");
$add = @$antibot_db->exec("ALTER TABLE counters ADD yandex INTEGER NOT NULL default '0';");
$add = @$antibot_db->exec("ALTER TABLE counters ADD mailru INTEGER NOT NULL default '0';");
$add = @$antibot_db->exec("ALTER TABLE counters ADD bing INTEGER NOT NULL default '0';");
$update = @$antibot_db->exec("UPDATE counters SET test = test + ".$test.", auto = auto + ".$auto.", click = click + ".$click.", uusers = uusers + ".$uusers.", husers = husers + ".$husers.", whits = whits + ".$whits.", bbots = bbots + ".$bbots.", fakes = fakes + ".$fakes.", google = google + ".$google.", yandex = yandex + ".$yandex.", mailru = mailru + ".$mailru.", bing = bing + ".$bing." WHERE date = '".$date."';");
}

if ($antibot_db->changes() == 0) {
$add = @$antibot_db->exec("INSERT INTO counters (test, auto, click, uusers, husers, whits, bbots, fakes, google, yandex, mailru, bing, date) VALUES ('".$test."', '".$auto."', '".$click."', '".$uusers."', '".$husers."', '".$whits."', '".$bbots."', '".$fakes."', '".$google."', '".$yandex."', '".$mailru."', '".$bing."', '".$date."');");
}
$ab_time_cron = $ab_time_cron - 86400;
}
