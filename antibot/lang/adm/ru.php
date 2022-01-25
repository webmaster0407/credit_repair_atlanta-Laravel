<?php
// Last update date: 2020.08.16
// admin.php
$pt['en'] = 'ru';
$pt['Log in'] = 'Войти';
$pt['Log out'] = 'Выйти';
$pt['Home'] = 'Главная';
$pt['Statistics'] = 'Статистика';
$pt['Rules'] = 'Правила';
$pt['Query Log'] = 'Лог запросов';
$pt['Fake bots'] = 'Фейк боты';
$pt['Update'] = 'Обновление';
$pt['Don\'t want to see ads? - Connect the cloud version of the antibot.'] = 'Не хотите видеть рекламу? - подключите облачную версию антибота.';
$pt['More about pricing.'] = 'Подробнее о тарифах.';
// adm/addrule.php
$pt['Add rule'] = 'Добавление правила';
// adm/clearfake.php
$pt['Empty the records'] = 'Очистить таблицу записей';
// adm/conf.php
$pt['Edit conf.php'] = 'Редактирование conf.php';
$pt['Settings have been saved.'] = 'Настройки сохранены.';
$pt['File /antibot/data/conf.php - be careful, in case of syntax error you will have to modify it via FTP.'] = 'Файл /antibot/data/conf.php - будьте внимательны, если допустить php ошибку при редактировании, то исправлять придется этот файл по фтп.';
$pt['Save Settings'] = 'Сохранить настройки';
$pt['File editing is disabled by security settings.'] = 'Редактирование файла запрещено настройками безопасности.';
$pt['To enable editing, set it to conf.php:'] = 'Для разрешения редактирования установите в conf.php:';
$pt['You are allowed to edit files via the admin panel. It might not be safe.'] = 'У вас разрешено редактировать файлы через админ панель. Это может быть небезопасно.';
$pt['To disable editing, set it to conf.php:'] = 'Для отключения редактирования установите в conf.php:';
// adm/counter.php
$pt['Edit counter.txt'] = 'Редактирование counter.txt';
$pt['File /antibot/data/counter.txt - is designed to insert html or js code of statistics counters (Google Analytics, Yandex.Metrica, etc.).'] = 'Файл /antibot/data/counter.txt - предназначен для вставки html и js кода счетчиков статистики (Google Analytics, Яндекс.Метрика и т.п.).';
// adm/counters.php
$pt['Until the next update statistics remains:'] = 'До следующего обновления статистики осталось:';
$pt['sec.'] = 'сек.';
$pt['Date'] = 'Дата';
$pt['Failed'] = 'Не прошли';
$pt['visitors (mostly the bad bots) who didn\'t make to pass AntiBot.'] = 'посетители (в основном бесполезные боты) которые дальше заглушки АнтиБота не прошли.';
$pt['Automatic'] = 'Автоматически';
$pt['visitors who successfully passed the test.'] = 'посетители, которые успешно автоматически прошли проверку.';
$pt['Clicked'] = 'Кликнули';
$pt['visitors who did not pass the automatic check, but clicked on the button.'] = 'посетители, которые не прошли автоматическую проверку, но кликнули по кнопке.';
$pt['Unique'] = 'Уников';
$pt['the number of unique visitors who passed the AntiBot check.'] = 'количество уникальных посетителей, прошедших АнтиБот проверку.';
$pt['Hits'] = 'Хитов';
$pt['the number of views by visitors who passed the AntiBot check.'] = 'количество просмотров посетителями, которые прошли АнтиБот проверку.';
$pt['Good bots'] = 'Хороших ботов';
$pt['the number of hits by good bots (allowed in the conf.php).'] = 'количество обращений хорошими ботами (разрешенными в conf.php).';
$pt['Blocked'] = 'Заблокировано';
$pt['blocked hits according to your rules (by ip, country, language, referrer, ptr).'] = 'заблокировано обращений по вашим правилам (по ip, стране, языку, рефереру, ptr).';
$pt['hits by fake bots that disguise themselves as good bots.'] = 'обращений ботов, которые маскировались под хороших ботов.';
// adm/fake.php
$pt['This list may include not only fake bots, but also good bots, if the parameters for defining good bots are not correctly or completely configured. For example, not all IP subnets are added to the list in the config or not all PTRs.'] = 'В этом списке могут попадаться не только фейк боты, но и хорошие боты, если не правильно или не полностью настроены параметры для определения хороших ботов. Например не все подсети IP добавили в конфиге в список или не все PTR.';
$pt['Country'] = 'Страна';
$pt['To the begining'] = 'В начало';
$pt['Show more'] = 'Показать еще';
$pt['Delete all of these entries. Reduce the size of the database file (VACUUM), may take a long time.'] = 'Удаление всех этих записей. Уменьшит размер файла базы (VACUUM), может занять продолжительное время.';
// adm/hits.php
$pt['Search:'] = 'Поиск:';
$pt['table:'] = 'таблица:';
$pt['operator:'] = 'условие:';
$pt['Strictly equal'] = 'Строго равно';
$pt['Contains'] = 'Содержит';
$pt['Search'] = 'Найти';
$pt['Status'] = 'Статус';
$pt['any'] = 'любой';
$pt['selection by:'] = 'выборка по:';
$pt['request to the AntiBot check page, check failed, the visitor remains on the check page.'] = 'запрос к заглушке АнтиБота, проверка не пройдена, посетитель остался на заглушке.';
$pt['request to the AntiBot check page, the check was completed automatically, the visitor received a redirect to the full page.'] = 'запрос к заглушке АнтиБота, проверка пройдена автоматически, посетитель получил редирект на полноценную страницу.';
$pt['the request to the AntiBot check page, the check did not pass automatically, the visitor clicked on the website login button, the visitor received a redirect to the full page.'] = 'запрос к заглушке АнтиБота, проверка автоматически не прошла, посетитель кликнул по кнопке входа на сайт, посетитель получил редирект на полноценную страницу.';
$pt['request to a full page of the website, the visitor already had permission to access the website.'] = 'запрос к полноценной странице сайта, пользователь уже имел разрешение на доступ к сайту.';
$pt['The following is the time taken to generate the AntiBot check software.'] = 'Далее указано время затраченное на генерацию скрипта антибот проверки.';
// adm/importrules.php
$pt['Example:'] = 'Пример:';
$pt['Import Blocking and Permission Rules'] = 'Импорт в базу правил блокировки и разрешения';
$pt['Import of blocking and permission rules to the database. If such a rule already exists in the database (value of the first column), then the rule will not be added. The format is line-by-line: Condition|Rule|Comment. Condition - see the rules page. The rule is black or white. Comment - a description of the rule, the value is optional.'] = 'Импорт в базу правил блокировки и разрешения. Если такое правило уже есть в базе (значение первого столбца), то правило добавлено не будет. Формат построчно вида: Условие|Правило|Комментарий. Условие - смотрите на странице правил. Правило - black или white. Комментарий - описание правила, значение не обязательное.';
$pt['Import rules to the database'] = 'Импортировать правила в базу';
// adm/index.php
$pt['Database file size'] = 'Размер файла базы';
$pt['telegram chat support in English.'] = 'телеграм чат поддержки на английском языке.';
$pt['telegram chat support in Russian.'] = 'телеграм чат поддержки на русском языке.';
$pt['support service for the cloud (paid) version.'] = 'служба поддержки облачной (платной) версии.';
$pt['You are using a local (free) version of AntiBot protection.'] = 'Вы используете локальную (бесплатную) версию антибот защиты.';
// adm/lang.php
$pt['Change the interface language'] = 'Смена языка интерфейса';
// adm/remove.php
$pt['Delete rule'] = 'Удаление правила';
// adm/rules.php
$pt['Your IP:'] = 'Ваш IP:';
$pt['Import rules to the database.'] = 'Импорт правил в базу данных.';
$pt['Export all rules from the database to a .txt file.'] = 'Экспорт всех правил из базы в .txt файл.';
$pt['Blocking and Permission Rules'] = 'Правила блокировки и разрешения';
$pt['IP address:'] = 'IP адрес:';
$pt['Comment:'] = 'Комментарий:';
$pt['Rule:'] = 'Правило:';
$pt['White (allow)'] = 'White (разрешать)';
$pt['Black (block)'] = 'Black (блокировать)';
$pt['Add'] = 'Добавить';
$pt['IPv4 address type «123.123.123.123» or IPv6 type «1234:abcd:1234:abcd:1234:abcd:1234:abcd» or in a shorter form - the first 3 parts of IPv4 address: «123.123.123» (if you need to add the entire subnet by mask /24) or for IPv6 the first 4 parts: «1234:abcd:1234:abcd» (subnet by mask /64).'] = 'IPv4 адрес вида «123.123.123.123» или IPv6 вида «1234:abcd:1234:abcd:1234:abcd:1234:abcd» или в укороченном виде - первые 3 части IPv4 адреса: «123.123.123» (если надо добавить всю подсеть по маске /24) или для IPv6 первые 4 части: «1234:abcd:1234:abcd» (подсеть по маске /64).';
$pt['Condition:'] = 'Условие:';
$pt['Rules by country, language, referrer, ptr - you can add only to the black list.'] = 'Правила по стране, языку, рефереру, ptr - можно добавлять только в черный список.';
$pt['Blocking by country'] = 'Блокировка по странам';
$pt['add 2 uppercase country codes (example: US). List of country codes:'] = 'добавлять 2 буквенные коды стран в верхнем регистре (пример: RU). Список кодов стран:';
$pt['Blocking by browser language'] = 'Блокировка по языку браузера';
$pt['2 lowercase alphabetic language codes (example: en). List of language codes:'] = '2 буквенные коды языка в нижнем регистре (пример: ru). Список кодов языков:';
$pt['Blocking by referrer'] = 'Блокировка по рефереру';
$pt['add a domain (host only, without protocol and internal pages), in the form: spamsite.com'] = 'добавлять домен (только хост, без протокола и внутренних страниц), в виде: spamsite.com';
$pt['Blocking by PTR'] = 'Блокировка по PTR';
$pt['add a domain from PTR, but not the whole, but only 2 or 3 level domains, i.e. if in statistics you see a PTR of the form: «ec2-52-15-190-240.us-east-2.compute.amazonaws.com», then you need to add «compute.amazonaws.com» or «amazonaws.com».'] = 'добавлять домен из PTR, но не целый, а только домены 2 или 3 уровня, т.е. если в статистике вы видите PTR вида: «ec2-52-15-190-240.us-east-2.compute.amazonaws.com», то добавлять надо «compute.amazonaws.com» или «amazonaws.com».';
$pt['My list is for PTR blocking.'] = 'Мой список для блокировки по PTR.';
$pt['Check condition'] = 'Условие проверки';
$pt['Rule'] = 'Правило';
$pt['Comment'] = 'Комментарий';
$pt['Remove'] = 'Удалить';
$pt['Total rules:'] = 'Всего правил:';
$pt['data to search during the check (field values are unique).'] = 'данные для поиска при проверке (значения поля уникальные).';
$pt['allow white list (white) or black list (black) prohibit.'] = 'белый список (white) разрешать или черный список (black) запрещать.';
$pt['description or reason for adding.'] = 'описание или причина добавления.';
$pt['Remove all rules'] = 'Удалить все правила';
// adm/tpl.php
$pt['Edit tpl.txt'] = 'Редактирование tpl.txt';
$pt['File /antibot/data/tpl.txt - template of the AntiBot check page. Make a backup of the template before edit.'] = 'Файл /antibot/data/tpl.txt - шаблон страницы проверки АнтиБота. Сделайте бекап шаблона перед редактированием.';
// adm/update.php
$pt['The update was successful.'] = 'Обновление прошло успешно.';
$pt['Update failed.'] = 'Ошибка обновления.';
$pt['The MD5 archive hash does not match the reference.'] = 'MD5 хэш архива не совпадает с эталоном.';
$pt['Class ZipArchive not exists. Install ZIP extension for PHP.'] = 'Класс ZipArchive не существует. Установите расширение zip для php.';
$pt['These files will be replaced or added:'] = 'Эти файлы будут заменены или добавлены:';
$pt['will be replaced'] = 'будет заменен';
$pt['will be added'] = 'будет добавлен';
$pt['set write permissions on this file'] = 'поставьте права на запись на этот файл';
$pt['Updating is not possible. Correct the errors indicated above.'] = 'Обновление невозможно. Исправьте ошибки, указанные выше.';
$pt['No update required.'] = 'Обновление не требуется.';
$pt['Local files that will not be changed:'] = 'Локальные файлы, которые не будут изменены:';
$pt['Make update'] = 'Провести обновление';
$pt['The tpl.txt template is different from the current one:'] = 'Шаблон tpl.txt отличается от актуального:';
$pt['The tpl.txt template does not require updating.'] = 'Шаблон tpl.txt не требует обновления.';
$pt['Your software version:'] = 'Версия вашего скрипта:';

// adm/error.php
$pt['Edit error.txt'] = 'Редактирование error.txt';
$pt['File /antibot/data/error.txt - custom error page for blocked users, used when $ab_config[\'custom_error_page\'] = 1; in conf.php'] = 'Файл /antibot/data/error.txt - настраиваемая страница ошибок для заблокированных пользователей, используется, когда $ab_config[\'custom_error_page\'] = 1; в conf.php';
$pt['Now in use.'] = 'Сейчас используется.';
$pt['Now NOT used.'] = 'Сейчас НЕ используется.';
