<?php
// Last update date: 2020.06.29
require_once(__DIR__.'/data/conf.php');

$ok = 1;
$result = '<ul>';
if (exec('whoami') == get_current_user()) {
$result .= '<li style="color:green;">file owner = php user (good practice)</li>';
} elseif (get_current_user() == 'root') {
$result .= '<li style="color:red;">Uploading files by the root user is a bad practice!</li>';
} else {
$result .= '<li style="color:red;">file owner != php user (bad practice)</li>';
}

if (extension_loaded('zip')) {
$result .= '<li style="color:green;">ZIP - installed</li>';
} else {
$result .= '<li style="color:red;">ZIP - not installed</li>';
}

if (extension_loaded('sqlite3')) {
$result .= '<li style="color:green;">SQLite3 - installed</li>';
} else {
$result .= '<li style="color:red;">SQLite3 - not installed</li>';
$ok = 0;
}

if (extension_loaded('mbstring')) {
$result .= '<li style="color:green;">Mbstring - installed</li>';
} else {
$result .= '<li style="color:red;">Mbstring - not installed</li>';
$ok = 0;
}

if (extension_loaded('memcached')) {
$result .= '<li style="color:green;">Memcached - installed</li>';
} else {
$result .= '<li style="color:red;">Memcached - not installed</li>';
if ($ab_config['memcached_counter'] == 1) {$ok = 0;}
}

if (extension_loaded('memcached')) {
$m = new Memcached();
$m->addServer($ab_config['memcached_host'], $ab_config['memcached_port']);
$m->set('memcached_test', 1);
if ($m->get('memcached_test') == 1) {
$result .= '<li style="color:green;">Memcached - connected</li>';
} else {
$result .= '<li style="color:red;">Memcached - no connection</li>';
if ($ab_config['memcached_counter'] == 1) {$ok = 0;}
}
}
$result .= '</ul>';

if ($ok != 1) {
echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Test Server</title>
</head>
<body>
'.$result.'
<p><a href="admin.php">Log in Admin Panel</a></p>
</body>
</html>
';
die();
}

require_once('code/include.php');

echo '<!DOCTYPE html>
<html>
<head>
<title>Antibot Demo Page</title>
<meta charset="utf-8">
</head>
<body>
'.$result.'
<ul>
<li>You are identified as a '.(($ab_config['whitebot'] == 1) ? 'Good Bot' : 'USER').'.</li>
<li><a href="admin.php">Log in Admin Panel</a>.</li>
</ul>
</body>
</html>';
