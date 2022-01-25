<?php
// Last update date: 2020.05.18
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Import Blocking and Permission Rules');
$content = '';
if (isset($_POST['submit'])) {
$_POST['rules'] = isset($_POST['rules']) ? trim($_POST['rules']) : '';
$_POST['rules'] = explode("\n", $_POST['rules']);
$antibot_db->exec('BEGIN IMMEDIATE;');
foreach($_POST['rules'] as $rule) {
$rule = explode('|', trim($rule));
if (isset($rule[1])) {
if ($rule[1] == 'black' OR $rule[1] == 'white') {
$rule[0] = $antibot_db->escapeString(strip_tags($rule[0]));
$rule[2] = isset($rule[2]) ? $antibot_db->escapeString(trim(strip_tags($rule[2]))) : '';
$add = @$antibot_db->exec("INSERT INTO rules (search, rule, comment) VALUES ('".$rule[0]."', '".$rule[1]."', '".$rule[2]."');");
}
}
}
$antibot_db->exec('COMMIT;');
$content .= '<div class="alert alert-success" role="alert">
  '.abTranslate('Settings have been saved.').' <a href="?page=rules">'.abTranslate('Rules').'</a>
</div>';
}
$content .= '<p>'.abTranslate('Import of blocking and permission rules to the database. If such a rule already exists in the database (value of the first column), then the rule will not be added. The format is line-by-line: Condition|Rule|Comment. Condition - see the rules page. The rule is black or white. Comment - a description of the rule, the value is optional.').'<br />
'.abTranslate('Example:').'<br />
amazonaws.com|black|hosting for bad bots<br />
66.249.68|white|Googlebot
</p>
<form action="" method="post">
  <div class="form-group">
<textarea name="rules" rows="13" class="form-control">
</textarea>  
</div>
 <div class="form-group">';

$content .=  '<p><button name="submit" type="submit" class="btn btn-info btn-sm">'.abTranslate('Import rules to the database').'</button></p>
';
$content .=  '  </div>
</form>';
