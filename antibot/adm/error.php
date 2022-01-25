<?php
// Last update date: 2020.04.30
if(!defined('ANTIBOT')) die('access denied');

if (!file_exists(__DIR__.'/../data/error.txt')) {
$content = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ERROR</title>
  </head>
  <body>
  <p><center style="color:red;">Sorry, your request has been denied.</center></p>
  <p><center>Error Code: <?php echo $ab_config[\'error_headers\'][$ab_config[\'header_error_code\']]; ?></center></p>
  </body>
</html>';
file_put_contents(__DIR__.'/../data/error.txt', $content, LOCK_EX);
}

$title = abTranslate('Edit error.txt');
$content = '';
if (isset($_POST['submit']) AND $ab_config['disable_editing'] != 1) {
$_POST['tpl'] = isset($_POST['tpl']) ? trim($_POST['tpl']) : '';
if ($_POST['tpl'] != '') {
file_put_contents(__DIR__.'/../data/error.txt', $_POST['tpl'], LOCK_EX);
$content .= '<div class="alert alert-success" role="alert">
  '.abTranslate('Settings have been saved.').'
</div>';
}
}
$content .= '<p>'.abTranslate('File /antibot/data/error.txt - custom error page for blocked users, used when $ab_config[\'custom_error_page\'] = 1; in conf.php').'</p>
<div class="alert alert-info" role="alert">';
if ($ab_config['custom_error_page'] == 1) {
$content .= abTranslate('Now in use.');
} else {
$content .= abTranslate('Now NOT used.');
}
$content .= '</div><form action="" method="post">
  <div class="form-group">
<textarea name="tpl" rows="13" class="form-control">
'.file_get_contents(__DIR__.'/../data/error.txt').'
</textarea>  
</div>
 <div class="form-group">';
if ($ab_config['disable_editing'] != 1) {
$content .=  '<p><button name="submit" type="submit" class="btn btn-info btn-sm">'.abTranslate('Save Settings').'</button></p>
<div class="alert alert-danger" role="alert">
  '.abTranslate('You are allowed to edit files via the admin panel. It might not be safe.').'<br />
   '.abTranslate('To disable editing, set it to conf.php:').' $ab_config[\'disable_editing\'] = 1;
</div>';
} else {
$content .= '<div class="alert alert-success" role="alert">
  '.abTranslate('File editing is disabled by security settings.').'<br />
   '.abTranslate('To enable editing, set it to conf.php:').' $ab_config[\'disable_editing\'] = 0;
</div>';
}
$content .=  '  </div>
</form>';
