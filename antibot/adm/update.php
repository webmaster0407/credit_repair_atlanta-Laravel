<?php
// Last update date: 2020.05.08
if(!defined('ANTIBOT')) die('access denied');

clearstatcache(true); // Clears file status cache

$content = '';

if (isset($_POST['submit']) AND isset($_POST['upd'])) {
$actual = @json_decode(file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/update.json'), true);
$archive = @file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/update.zip');
file_put_contents(__DIR__.'/../data/update.zip', $archive, LOCK_EX);
if ($actual['md5'] == md5_file(__DIR__.'/../data/update.zip')) {
$zip = new ZipArchive;
if ($zip->open(__DIR__.'/../data/update.zip') === TRUE) {
    $zip->extractTo(__DIR__.'/../');
    $zip->close();
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('The update was successful.').'
</div>';
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Update failed.').'
</div>';
}
clearstatcache();
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Update failed.').' '.abTranslate('The MD5 archive hash does not match the reference.').'
</div>';
}
unlink(__DIR__.'/../data/update.zip');
}

$title = abTranslate('Update');

// не установлен ZIP
if (!class_exists('ZipArchive')) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Class ZipArchive not exists. Install ZIP extension for PHP.').'
</div>';
}

$antibotdir = realpath(__DIR__.'/../');

// получение массива актуальных файлов:
function getDirContents($dir, &$results = array()) {
global $antibotdir;
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($path)) {
//            $results[$path] = md5_file($path);
            $filename = explode($antibotdir.'/', $path);
//            echo $filename[1].'<br>';
            $results[$filename[1]] = md5_file($path).'_'.md5($filename[1]);
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[$path] = md5_file($path);
        }
    }
    return $results;
}

$local = getDirContents(realpath(__DIR__.'/../')); // локальный массив

$actual = @json_decode(file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/update.json'), true); // актуальный массив

unset($actual['md5']);

// Сравнивает array1 с одним или несколькими другими массивами и возвращает значения из array1, которые отсутствуют во всех других массивах.
$differences = array_diff($actual, $local); // Отличающиеся файлы, которые есть в архиве
$content .= '<div class="alert alert-info" role="alert">'.abTranslate('Your software version:').' '.$ab_version.'</div>
<p>'.abTranslate('These files will be replaced or added:').'</p>
<ul>';
$upderror = 0;
$updcount = 0;
foreach ($differences as $k => $v) {
if (file_exists(__DIR__.'/../'.$k) AND is_writable(__DIR__.'/../'.$k)) {
$content .= '<li class="text-success">'.$k.' ('.abTranslate('will be replaced').')</li>';
} elseif (!file_exists(__DIR__.'/../'.$k)) {
$content .= '<li class="text-success">'.$k.' ('.abTranslate('will be added').')</li>';
} else {
$content .= '<li class="text-danger">'.$k.' ('.abTranslate('set write permissions on this file').')</li>';
$upderror = 1;
}
$updcount++;
}
$content .= '</ul>';
if ($upderror == 1) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Updating is not possible. Correct the errors indicated above.').'
</div>';
} elseif ($updcount == 0) {
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('No update required.').'
</div>';
} elseif ($host == 'antibot-cloud') {
$content .= '<div class="alert alert-danger" role="alert">
Тут нельзя обновлять.
</div>';
} else {
$content .= '<p><form class="form-inline" action="?page=update" method="post">
<input name="upd" type="hidden" value="1">
<input style="cursor:pointer;" class="btn btn-sm btn-success" type="submit" name="submit" value="'.abTranslate('Make update').'">
</form></p>';
}

$differences = array_diff($local, $actual); // различия
foreach ($differences as $k => $v) {
if (isset($actual[$k])) {unset($differences[$k]);}
}
$content .= '<p>'.abTranslate('Local files that will not be changed:').'</p>
<ul>';
foreach ($differences as $k => $v) {
$content .= '<li>'.$k.'</li>';
}
$content .= '</ul>';

// обновление шаблона:
$actual_tpl = @json_decode(file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/tpl.json'), true);
$local_tpl = md5_file(__DIR__.'/../data/tpl.txt');
if ($actual_tpl['md5'] != $local_tpl) {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('The tpl.txt template is different from the current one:').' <a href="https://github.com/MikFoxi/AntiBot/blob/master/tpl.txt" target="_blank">tpl.txt</a>
</div>';
$content .= '<p><form class="form-inline" action="?page=updatetpl" method="post">
<input name="upd" type="hidden" value="1">
<input style="cursor:pointer;" class="btn btn-sm btn-success" type="submit" name="submit" value="'.abTranslate('Make update').'">
</form></p>';
} else {
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('The tpl.txt template does not require updating.').'
</div>';
}
