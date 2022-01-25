<?php
// Last update: 2020.04.27
if(!defined('ANTIBOT')) die('access denied');
?><!DOCTYPE html>
<html lang="<?php echo abTranslate('en'); ?>">
<head>
  <meta charset="UTF-8">
  <title>AntiBot.Cloud</title>
<link rel="stylesheet" href="/<?php echo $ab_dir; ?>/static/formstyle.css" />
</head>
  <body>
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1>AntiBot.Cloud</h1>
			</div>
<form action="" method="post">
	<div class="login-form">
		<div class="control-group">
		<input name="auth_user" type="text" class="login-field" value="" placeholder="Email" id="login-name">
		<label class="login-field-icon fui-user" for="login-name"></label>
		</div>
		<div class="control-group">
		<input name="auth_pass" type="password" class="login-field" value="" placeholder="Password" id="login-pass">
		<label class="login-field-icon fui-lock" for="login-pass"></label>
		</div>
		<input name="auth_post" type="hidden" value="1">
		<button type="submit" name="submit" class="btn btn-primary btn-large btn-block" style="cursor:pointer;"><?php echo abTranslate('Log in'); ?></button>
		<p class="login-link"><a href="https://antibot.cloud/" title="Detect & Block Bad Bot Traffic" target="_blank" style="font-size:12px;">Protected by AntiBot.Cloud</a></p>
	</div>
</form>		</div>
	</div>
</body>
</html>
