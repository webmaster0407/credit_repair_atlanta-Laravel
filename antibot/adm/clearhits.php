<?php
// очистка таблицы хитов
// Last update date: 2020.04.06
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Empty the records');

if (isset($_POST['submit'])) {
$del = $antibot_db->exec("DELETE FROM hits;");
$vacuum = $antibot_db->exec("VACUUM;");
}

header('HTTP/1.1 301 Moved Permanently');
header('Location: ?page=hits');
