<?php
// Last update: 2020.03.23

// функция языкового перевода:
function abTranslate($current_phrase) {
global $pt; 
return isset($pt[$current_phrase]) ? $pt[$current_phrase] : $current_phrase;
}

// перевод укороченного ipv6 в нормальный вид:
function abExpand($ip){
$hex = unpack("H*hex", inet_pton($ip));         
$ip = substr(preg_replace("/([A-f0-9]{4})/", "$1:", $hex['hex']), 0, -1);
return $ip;
}

// функция проверки белого бота на белость:
function TestWhiteBot($ip, $ptr_ok) {
// $ptr_ok - массив
$ptr = gethostbyaddr($ip); // получаем ptr хост по ip
$result = dns_get_record($ptr, DNS_A + DNS_AAAA); // ipv4 & ipv6 у ptr хоста
$ip2 = array(); // массив всех IP принадлежащих PTR хосту
if ($ptr == $ip) $ip2[] = $ip;
foreach($result as $line) {
if (isset($line['ipv6'])) {$ip2[] = abExpand($line['ipv6']);}
if (isset($line['ip'])) {$ip2[] = $line['ip'];}
}
$test_ptr = 0;
foreach($ptr_ok as $ptr_line) {
if(stripos($ptr, $ptr_line, 0) !== false) {$test_ptr = 1; break;}
}
if (in_array($ip, $ip2) AND $test_ptr == 1) {return 1;} else {return 0;}
}
