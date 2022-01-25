<?php
// Last update date: 2020.04.30
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Edit conf.php');
$content = '';
if (isset($_POST['submit']) AND $ab_config['disable_editing'] != 1) {
$_POST['conf'] = isset($_POST['conf']) ? trim($_POST['conf']) : '';
if ($_POST['conf'] != '') {
file_put_contents(__DIR__.'/../data/conf.php', $_POST['conf'], LOCK_EX);
$content .= '<div class="alert alert-success" role="alert">
  '.abTranslate('Settings have been saved.').'
</div>';
}
}
$content .= '<p>'.abTranslate('File /antibot/data/conf.php - be careful, in case of syntax error you will have to modify it via FTP.').'</p>
<form action="" method="post">
  <div class="form-group">
<textarea name="conf" rows="13" class="form-control">
'.file_get_contents(__DIR__.'/../data/conf.php').'
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
