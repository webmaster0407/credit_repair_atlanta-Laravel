<?php
date_default_timezone_set('America/Los_Angeles'); // time zone, https://www.php.net/manual/ru/timezones.php
$db_file = 'content.db'; // the name of the database file in the data folder.
$adminname = 'adminpacific'; // login to access the stat.php etc.
$adminpass = 'pacific777#'; // password to access the stat.php etc.
$tpl_index = 'tpl_index.txt'; // the template file for the muzzle in the data folder.
$tpl_pages  = 'tpl_pages.txt'; // the template file for the internal file in the data folder.
$ext_links = 13; // the number of links in the live domain linking.
$int_links = 13; // internal linking of the menu type with CNC. number of links.

$index_links = 50; // beautiful (with all the data) links from the muzzle
$index_links_rows = 'title, imgurl'; // columns to get for muzzle links, all possible values: id, title, img url, isbn, year, lang, pages, description

$site_name = 'Credit Repair'; // site name
$memcached_host = '/var/run/memcached/memcached.sock'; // by default, often = '127.0.0.1';
$memcached_port = 0; // by default, often = 11211;
$memcached_prefix = 'xxx_'; // the prefix for the data in memcached (if the server has a lot of packs Fedorov)
$cloudflare_only = 0; // 1 - allow access only through cloudflare, 0-allow all.
$https_only = 0; // 1 - allow only https access (for example, if cloudflare is used), 0-allow http as well.
$notmodified304 = 0; // 1 - to include a 304 Not Modified (reduced load), 0 - do not include.
$autolink_disable = 0; // 0 - automatically link by linking live domains. 1-disable linking (clean the current links in the memkeshed yourself or replace them).
$lnk_max = 3000; // the number of pages on the domain in html and xml site maps, in real pages = $max_num

require_once($_SERVER['DOCUMENT_ROOT'].'/antibot/code/include.php');
 