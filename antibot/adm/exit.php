<?php
// Last update date: 2020.05.15
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Log out');

setcookie('auth_admin_token', 'null', $ab_config['time']-100, '/'.$ab_dir);

header('HTTP/1.1 301 Moved Permanently');
header('Location: ?page=index');
