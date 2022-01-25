<?php
// Last update date: 2020.08.16
// admin.php
$pt['en'] = 'pl';
$pt['Log in'] = 'Zaloguj się';
$pt['Log out'] = 'Wyjdź';
$pt['Home'] = 'Główna';
$pt['Statistics'] = 'Statystyki';
$pt['Rules'] = 'Zasady';
$pt['Query Log'] = 'Dziennik logów';
$pt['Fake bots'] = 'Fałszywe roboty';
$pt['Update'] = 'Aktualizacja';
$pt['Don\'t want to see ads? - Connect the cloud version of the antibot.'] = 'Nie chcesz oglądać reklam? - Podłącz wersję w chmurze (Antibot.Cloud).';
$pt['More about pricing.'] = 'Więcej o cenach.';
// adm/addrule.php
$pt['Add rule'] = 'Dodaj regułę';
// adm/clearfake.php
$pt['Empty the records'] = 'Wyczyść tabelę danych';
// adm/conf.php
$pt['Edit conf.php'] = 'Edytuj conf.php';
$pt['Settings have been saved.'] = 'Ustawienia zostały zapisane.';
$pt['File /antibot/data/conf.php - be careful, in case of syntax error you will have to modify it via FTP.'] = 'Plik /antibot/data/conf.php - bądź ostrożny. Jeśli podczas edycji pozwolisz sobie na popełnienie błędu przez php, będziesz musiał naprawić ten plik za pomocą ftp.';
$pt['Save Settings'] = 'Zapisz ustawienia';
$pt['File editing is disabled by security settings.'] = 'Edycja pliku jest zabroniona przez ustawienia bezpieczeństwa.';
$pt['To enable editing, set it to conf.php:'] = 'Aby włączyć edycję, ustaw conf.php:';
$pt['You are allowed to edit files via the admin panel. It might not be safe.'] = 'Możesz edytować pliki za pomocą panelu administracyjnego. To może nie być bezpieczne.';
$pt['To disable editing, set it to conf.php:'] = 'Aby wyłączyć edycję, ustaw w conf.php:';
// adm/counter.php
$pt['Edit counter.txt'] = 'Edytuj counter.txt';
$pt['File /antibot/data/counter.txt - is designed to insert html or js code of statistics counters (Google Analytics, Yandex.Metrica, etc.).'] = 'Plik /antibot/data/counter.txt przeznaczony jest do wstawiania kodu html i js liczników statystyk (Google Analytics, Yandex.Metrica itp.).';
// adm/counters.php
$pt['Until the next update statistics remains:'] = 'Statystyki pozostają do następnej aktualizacji:';
$pt['sec.'] = 'sek.';
$pt['Date'] = 'Data';
$pt['Failed'] = 'Nie powiodło się';
$pt['visitors (mostly the bad bots) who didn\'t make to pass AntiBot.'] = 'goście (głównie roboty), którym się nie powiodło przejść wstępną kontrolę.';
$pt['Automatic'] = 'Automatycznie';
$pt['visitors who successfully passed the test.'] = 'Osoby odwiedzające, które pomyślnie przeszły test.';
$pt['Clicked'] = 'Kliknięć';
$pt['visitors who did not pass the automatic check, but clicked on the button.'] = 'użytkownicy, którzy nie przeszli automatycznego sprawdzania, ale kliknęli przycisk.';
$pt['Unique'] = 'Unikalnych';
$pt['the number of unique visitors who passed the AntiBot check.'] = 'liczba unikalnych użytkowników, którzy przeszli kontrolę AntyBotową.';
$pt['Hits'] = 'Liczba odsłon';
$pt['the number of views by visitors who passed the AntiBot check.'] = 'liczba wyświetleń przez użytkowników, którzy przeszli kontrolę AntyBotową.';
$pt['Good bots'] = 'Dobre roboty';
$pt['Blocked'] = 'Zablokowanych';
$pt['the number of hits by good bots (allowed in the conf.php).'] = 'liczba trafień dobrych botów (dozwolone w conf.php).';
$pt['blocked hits according to your rules (by ip, country, language, referrer, ptr).'] = 'zablokowane odsłony zgodnie z twoimi zasadami (według adresu IP, kraju, języka, referrera, ptr).';
$pt['hits by fake bots that disguise themselves as good bots.'] = 'odsłony przez fałszywych robotów udających dobrych.';
// adm/fake.php
$pt['This list may include not only fake bots, but also good bots, if the parameters for defining good bots are not correctly or completely configured. For example, not all IP subnets are added to the list in the config or not all PTRs.'] = 'Ta lista może obejmować nie tylko fałszywe roboty, ale także dobre roboty, jeśli parametry definiowania dobrych robotów nie są poprawnie lub całkowicie skonfigurowane. Na przykład nie wszystkie podsieci IP są dodawane do listy w konfiguracji lub nie wszystkie PTR.';
$pt['Country'] = 'Kraj';
$pt['To the begining'] = 'Wróć do początku';
$pt['Show more'] = 'Pokaż więcej';
$pt['Delete all of these entries. Reduce the size of the database file (VACUUM), may take a long time.'] = 'Usuń wszystkie te wpisy. Zmniejsz rozmiar pliku bazy danych (VACUUM), może to zająć dużo czasu.';
// adm/hits.php
$pt['Search:'] = 'Szukaj:';
$pt['table:'] = 'tabela:';
$pt['operator:'] = 'operator:';
$pt['Strictly equal'] = 'Ściśle równe';
$pt['Contains'] = 'Zawiera';
$pt['Search'] = 'Szukaj';
$pt['Status'] = 'Status';
$pt['any'] = 'każdy';
$pt['selection by:'] = 'wybór przez:';
$pt['request to the AntiBot check page, check failed, the visitor remains on the check page.'] = 'prośba o przejście na stronę kontroli AntyBoty, sprawdzenie się nie powiodło, odwiedzający pozostaje na stronie kontroli.';
$pt['request to the AntiBot check page, the check was completed automatically, the visitor received a redirect to the full page.'] = 'zapytanie do strony kontroli AntyBota, kontrola została zakończona automatycznie, odwiedzający otrzymał przekierowanie do pełnej strony.';
$pt['the request to the AntiBot check page, the check did not pass automatically, the visitor clicked on the site login button, the visitor received a redirect to the full page.'] = 'zapytanie do strony kontroli AntyBota, kontrola nie przeszła automatycznie, odwiedzający kliknął przycisk logowania do witryny, odwiedzający otrzymał przekierowanie do pełnej strony.';
$pt['request to a full page of the website, the visitor already had permission to access the website.'] = 'prośba o przejście do pełnej strony witryny, użytkownik miał już uprawnienia dostępu do witryny.';
$pt['The following is the time taken to generate the AntiBot check software.'] = 'Poniżej przedstawiono czas potrzebny na wygenerowanie skryptu sprawdzania AntyBoty.';
// adm/importrules.php
$pt['Example:'] = 'Przykład:';
$pt['Import Blocking and Permission Rules'] = 'Zaimportuj do bazy danych reguł blokowania i uprawnień';
$pt['Import of blocking and permission rules to the database. If such a rule already exists in the database (value of the first column), then the rule will not be added. The format is line-by-line: Condition|Rule|Comment. Condition - see the rules page. The rule is black or white. Comment - a description of the rule, the value is optional.'] = 'Zaimportuj do bazy danych reguł blokowania i uprawnień. Jeśli taka reguła już istnieje w bazie danych (wartość pierwszej kolumny), reguła nie zostanie dodana. Format jest wiersz po wierszu: Stan|Reguła|Komentarz. Stan - zobacz stronę z zasadami. Reguła jest czarna lub biała. Komentarz - opis reguły, wartość jest opcjonalna.';
$pt['Import rules to the database'] = 'Zaimportuj reguły do ​​bazy danych';
// adm/index.php
$pt['Database file size'] = 'Rozmiar pliku bazy danych';
$pt['telegram chat support in English.'] = 'obsługa czatu telegramowego w języku angielskim.';
$pt['telegram chat support in Russian.'] = 'obsługa czatu telegramowego w języku rosyjskim.';
$pt['support service for the cloud (paid) version.'] = 'usługa wsparcia dla wersji chmurowej (płatnej).';
$pt['You are using a local (free) version of AntiBot protection.'] = 'Używasz lokalnej (bezpłatnej) wersji ochrony przed robotami.';
// adm/lang.php
$pt['Change the interface language'] = 'Zmień język interfejsu';
// adm/remove.php
$pt['Delete rule'] = 'Usuń regułę';
// adm/rules.php
$pt['Your IP:'] = 'Twoje IP:';
$pt['Import rules to the database.'] = 'Zaimportuj reguły do ​​bazy danych.';
$pt['Export all rules from the database to a .txt file.'] = 'Wyeksportuj wszystkie reguły z bazy danych do pliku .txt.';
$pt['Blocking and Permission Rules'] = 'Zasady blokowania i uprawnień';
$pt['IP address:'] = 'Adres IP:';
$pt['Comment:'] = 'Komentarz:';
$pt['Rule:'] = 'Reguła:';
$pt['White (allow)'] = 'Dobry (pozwalają)';
$pt['Black (block)'] = 'Zły (blok)';
$pt['Add'] = 'Dodaj';
$pt['IPv4 address type «123.123.123.123» or IPv6 type «1234:abcd:1234:abcd:1234:abcd:1234:abcd» or in a shorter form - the first 3 parts of IPv4 address: «123.123.123» (if you need to add the entire subnet by mask /24) or for IPv6 the first 4 parts: «1234:abcd:1234:abcd» (subnet by mask /64).'] = 'Typ adresu IPv4 «123.123.123.123» lub typ IPv6 «1234:abcd:1234:abcd:1234:abcd:1234:abcd» lub w skrócie - pierwsze 3 części adresu IPv4: «123.123.123» (jeśli trzeba dodać całą podsieć według maski /24) lub w przypadku IPv6 pierwsze 4 części: «1234:abcd:1234:abcd» (podsieć według maski /64).';
$pt['Condition:'] = 'Stan:';
$pt['Rules by country, language, referrer, ptr - you can add only to the black list.'] = 'Reguły według kraju, języka, referrera, ptr - możesz dodać tylko do czarnej listy.';
$pt['Blocking by country'] = 'Blokowanie według kraju';
$pt['add 2 uppercase country codes (example: US). List of country codes:'] = 'dodaj 2 duże kody krajów (przykład: PL). Lista kodów krajów:';
$pt['Blocking by browser language'] = 'Blokowanie według języka przeglądarki';
$pt['2 lowercase alphabetic language codes (example: en). List of language codes:'] = '2 małe alfabetyczne kody języka (przykład: pl). Lista kodów językowych:';
$pt['Blocking by referrer'] = 'Blokowanie według referrera';
$pt['add a domain (host only, without protocol and internal pages), in the form: spamsite.com'] = 'dodaj domenę (tylko host, bez protokołu i stron wewnętrznych) w postaci: spamsite.com';
$pt['Blocking by PTR'] = 'Blokowanie według PTRa';
$pt['add a domain from PTR, but not the whole, but only 2 or 3 level domains, i.e. if in statistics you see a PTR of the form: «ec2-52-15-190-240.us-east-2.compute.amazonaws.com», then you need to add «compute.amazonaws.com» or «amazonaws.com».'] = 'dodaj domenę z PTR, ale nie całą. Tylko domeny 2 lub 3 poziomów, tj. jeśli w statystykach widzisz PTR w postaci: «ec2-52-15-190-240.us-east-2.compute.amazonaws.com», a następnie musisz dodać «compute.amazonaws.com» lub «amazonaws.com».';
$pt['My list is for PTR blocking.'] = 'Moja lista dotyczy blokowania PTRa.';
$pt['Check condition'] = 'Sprawdź stan';
$pt['Rule'] = 'Reguła';
$pt['Comment'] = 'Komentarz';
$pt['Remove'] = 'Usunąć';
$pt['Total rules:'] = 'Zasady ogółem:';
$pt['data to search during the check (field values are unique).'] = 'dane do przeszukania podczas kontroli (wartości pól są unikalne).';
$pt['allow white list (white) or black list (black) prohibit.'] = 'zezwól na blokowanie white list (białej) lub black list (czarnej).';
$pt['description or reason for adding.'] = 'opis lub powód dodania.';
$pt['Remove all rules'] = 'Usuń wszystkie reguły';
// adm/tpl.php
$pt['Edit tpl.txt'] = 'Edytuj tpl.txt';
$pt['File /antibot/data/tpl.txt - template of the AntiBot check page. Make a backup of the template before edit.'] = 'Plik /antibot/data/tpl.txt - szablon strony kontrolnej przeciw botom. Wykonaj kopię zapasową szablonu przed edycją.';
// adm/update.php
$pt['The update was successful.'] = 'Aktualizacja zakończyła się powodzeniem.';
$pt['Update failed.'] = 'Aktualizacja się nie powiodła.';
$pt['The MD5 archive hash does not match the reference.'] = 'Skrót funkcji haszującej archiwum MD5 nie pasuje do wzorca.';
$pt['Class ZipArchive not exists. Install ZIP extension for PHP.'] = 'Klasa ZipArchive nie istnieje. Zainstaluj rozszerzenie ZIP dla PHP.';
$pt['These files will be replaced or added:'] = 'Te pliki zostaną zastąpione lub dodane:';
$pt['will be replaced'] = 'zostanie zamieniony';
$pt['will be added'] = 'zostanie dodany';
$pt['set write permissions on this file'] = 'ustaw uprawnienia do zapisu dla tego pliku';
$pt['Updating is not possible. Correct the errors indicated above.'] = 'Aktualizacja nie jest możliwa. Popraw błędy wskazane powyżej.';
$pt['No update required.'] = 'Nie wymaga aktualizacji.';
$pt['Local files that will not be changed:'] = 'Pliki lokalne, które nie zostaną zmienione:';
$pt['Make update'] = 'Dokonaj aktualizacji';
$pt['The tpl.txt template is different from the current one:'] = 'Szablon tpl.txt różni się od obecnego:';
$pt['The tpl.txt template does not require updating.'] = 'Szablon tpl.txt nie wymaga aktualizacji.';
$pt['Your software version:'] = 'Twoja wersja skryptu:';

// adm/error.php
$pt['Edit error.txt'] = 'Edytuj error.txt';
$pt['File /antibot/data/error.txt - custom error page for blocked users, used when $ab_config[\'custom_error_page\'] = 1; in conf.php'] = 'Plik /antibot/data/error.txt - niestandardowa strona błędu dla zablokowanych użytkowników, używana, gdy $ab_config[\'custom_error_page\'] = 1; W conf.php';
$pt['Now in use.'] = 'Teraz w użyciu.';
$pt['Now NOT used.'] = 'NIE jest używane.';
