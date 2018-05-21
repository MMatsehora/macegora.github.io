<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file (config)' );

header('Content-Type: text/html;charset=utf-8'); // Кодировка страницы: header('Content-Type: text/html; charset=utf-8')
setlocale(LC_ALL,'ru-RU.UTF-8');                  // Установка локали, пример: en-US.UTF-8
header('Expires: Sun, 27 May 2007 01:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

$db_kod    = 'utf8';        // Кодировка баз данных
$setlocale = 'ru-RU.UTF-8'; // Установка локали, пример: en-US.UTF-8
$db_host   = 'localhost';   // Хост
$db_name   = 'geotex';      // Имя базы данных
$db_user   = 'Geotex';        // Имя пользователя
$db_pass   = 'Geotex2020';            // Пароль

$main           = 'main';          // Страница загружаемая по умолчанию
$name_main_page = 'Главная';       // Название страницы загружаемой по умолчанию
$dir            = 'products';      // Директорая в которой хранятся фотографии товаров
$domen          = 'geotex.com.ua'; // Доменное имя сайта

// Контактные данные
$contact_city    = 'Киев';
$contact_country = 'Украина';
$contact_adress  = 'ул.Петропавловская, 34-а';
$name_domen      = 'http://geotex.com.ua'; // Название домена сайта в формате 'http://name.dimen'
$name_company_ru = 'Компания "Геотекс Украина"';
$name_company_en = 'Geotex Ukraine';
$contact_tel1    = '068-055-70-11';
$contact_tel2    = '099-179-11-87';
$contact_tel3    = '063-681-11-45';
$contact_email   = 'geotexua@ukr.net';

$time_works      = 'Пн-Cб: 9.00-20.00 | Вс-вых.';
$header_title1   = 'Товары и услуги для гидротехнического';
$header_title2   = 'строительства и ландшафтного дизайна';

// Админка
// $main_admin_page = 'orders'; // Главная страница админки (загрузается первой)
// $spam_click  = 7;            // Количество кликов по рекламе с одного IP, чтобы считать их спамными
// $spam_period = 360;          // Период (дней), за который производится анализ рекламных кликов