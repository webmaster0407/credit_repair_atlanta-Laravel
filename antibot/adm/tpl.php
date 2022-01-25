<?php
// Last update date: 2020.04.30
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Edit tpl.txt');
$content = '';
if (isset($_POST['submit']) AND $ab_config['disable_editing'] != 1) {
$_POST['tpl'] = isset($_POST['tpl']) ? trim($_POST['tpl']) : '';
if ($_POST['tpl'] != '') {
file_put_contents(__DIR__.'/../data/tpl.txt', $_POST['tpl'], LOCK_EX);
$content .= '<div class="alert alert-success" role="alert">
  '.abTranslate('Settings have been saved.').'
</div>';
}
}
$content .= '<p>'.abTranslate('File /antibot/data/tpl.txt - template of the AntiBot check page. Make a backup of the template before edit.').'</p>
<form action="" method="post">
  <div class="form-group">
<textarea name="tpl" rows="13" class="form-control">
'.file_get_contents(__DIR__.'/../data/tpl.txt').'
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
