<?php
// Last update date: 2020.08.15
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Home');

$size = round(filesize(__DIR__.'/../data/sqlite.db') / 1024 / 1024, 2);

if ($ab_config['check_url'] != 'https://cloud.antibot.cloud/antibot7.php') {
$content .= '<div class="alert alert-danger" role="alert">'.abTranslate('You are using a local (free) version of AntiBot protection.').'</div>';
}

$content .= '<p>'.abTranslate('Database file size').' /antibot/data/sqlite.db: '.$size.' MB.</p>';
if ($lang_code == 'ru') {
$content .= '<p><a href="https://t.me/AntiBotCloud" target="_blank">@AntiBotCloud</a> - '.abTranslate('telegram chat support in Russian.').'</p>
<p><a href="https://foxi.biz/viewforum.php?id=1" target="_blank">Фокси Форум</a> - русскоязычный форум поддержки.</p>';
} else {
$content .= '<p><a href="https://t.me/AntiBotCloudSupport" target="_blank">@AntiBotCloudSupport</a> - '.abTranslate('telegram chat support in English.').'</p>
';
}
if ($ab_config['check_url'] == 'https://cloud.antibot.cloud/antibot7.php') {
$content .= '<div class="alert alert-success" role="alert">Telegram <a href="https://t.me/MikFoxi" target="_blank">@MikFoxi</a> & email <a href="mailto:admin@mikfoxi.com?subject=AntiBot: '.$host.'" target="_blank">admin@mikfoxi.com</a> - '.abTranslate('support service for the cloud (paid) version.').'</div>';
}
