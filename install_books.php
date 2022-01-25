<?php
error_reporting(E_ALL);
set_time_limit(0);
ignore_user_abort(true);
ini_set('display_errors', 'on');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'].'/data/errorlog.txt');
ini_set('memory_limit', '1500M');
header('Content-Type: text/html; charset=UTF-8');
header('X-Robots-Tag: noindex');
header('X-Frame-Options: DENY');
header('Cache-Control: no-cache, must-revalidate, max-age=0');

require_once $_SERVER['DOCUMENT_ROOT'].'/data/conf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (!file_exists('data/books.txt')) die('Файл не найден: data/books.txt');
$db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/data/'.$db_file); 
$db->busyTimeout(5000);
$db->exec("PRAGMA journal_mode = WAL;");
// Название книги¦Сссылка на картинку¦ISBN¦Год книги¦Язык книги¦Кол-во страниц в книге¦Описание книги
$query = $db->exec("CREATE TABLE IF NOT EXISTS books (
id INTEGER PRIMARY KEY AUTOINCREMENT, 
title TEXT UNIQUE NOT NULL default '', 
imgurl TEXT NOT NULL default '', 
isbn TEXT NOT NULL default '', 
year TEXT NOT NULL default '', 
lang TEXT NOT NULL default '', 
pages TEXT NOT NULL default '', 
descr TEXT NOT NULL default ''
);");
// генерация базы:
$file = fopen('data/books.txt', 'r');
$db->exec('BEGIN IMMEDIATE;');
$i = 0;
while (($key = fgets($file)) !== FALSE) {
$line = explode('¦', $key);
$line[0] = isset($line[0]) ? trim($line[0]) : '';
$line[0] = str_replace('"', '', $line[0]);
$line[0] = str_replace('_', ' ', $line[0]);
$line[0] = str_replace('/', ' ', $line[0]);
$line[0] = str_replace('  ', ' ', $line[0]);
$line[0] = str_replace('  ', ' ', $line[0]);
$line[0] = $db->escapeString(trim($line[0]));
$line[1] = isset($line[1]) ? $db->escapeString(trim($line[1])) : '';
$line[2] = isset($line[2]) ? $db->escapeString(trim($line[2])) : '';
$line[3] = isset($line[3]) ? $db->escapeString(trim($line[3])) : '';
$line[4] = isset($line[4]) ? $db->escapeString(trim($line[4])) : '';
$line[5] = isset($line[5]) ? $db->escapeString(trim($line[5])) : '';
$line[6] = isset($line[6]) ? $db->escapeString(trim($line[6])) : '';
$i++;
$add = @$db->exec("INSERT INTO books (title, imgurl, isbn, year, lang, pages, descr) VALUES ('".$line[0]."', '".$line[1]."', '".$line[2]."', '".$line[3]."', '".$line[4]."', '".$line[5]."', '".$line[6]."');");
if ($i == 50000) {
$db->exec('COMMIT;');
$db->exec('BEGIN IMMEDIATE;');
$i = 0;
}
}
$db->exec('COMMIT;');
echo '<p>Кеи из data/books.txt успешно загружены в data/books.db</p>';
$id = (int) $db->lastInsertRowID(); // номер созданной записи
echo '<p>Всего строк в базе: '.$id.'</p>';
} else {
echo '<form action="" method="post">
<button type="submit" name="submit">Загрузить кеи в базу</button>
<p>Из data/books.txt в data/books.db</p>
</form>';
}
