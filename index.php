<?php error_reporting(E_ERROR); ?>
<?php define('ALLOW', true);

// Куки - идентификация пользователя --- Начало
if (isset($_COOKIE['SESSION']) && (strlen($_COOKIE['SESSION'])) === 32) $cookie = $_COOKIE['SESSION'];
else {
    $cookie = openssl_random_pseudo_bytes(16);
    $cookie = bin2hex($cookie);
}
setcookie('SESSION', $cookie, time()+(60*60*24*2));
// Куки - идентификация пользователя --- Конец

require_once 'blocks/config.php';
require_once 'blocks/db.php';
require_once 'functions/functions.php';

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

    if (isset($_POST['url'])) $url = $_POST['url'];
}
if (!isset($url)) {
    // Блок обработки ЧПУ
    $_URL = explode("?", $_SERVER['REQUEST_URI']);
    $_URL = preg_replace("/\?+/",'/',$_URL[0]);
    $_URL = preg_replace("/\/+/",'/',$_URL);
    $_URL = preg_replace("/^\/(.*)\/?$/U",'\\1',$_URL);
    $_URL = explode("/", $_URL);

    if ($_URL[0] == 'admin2020') require_once './admin2020/controller.php';// Заход в панель администратора
    else {
        if     ($_URL[0] == '') $url = '';
        elseif ($_URL[0] != '') $url = $_URL[0];
        else {
            require_once './404.php';
            exit();
        }
    }
}
if (strpos($_SERVER['REQUEST_URI'], 'search') !== false) require_once 'templates/search.tpl';
else {
    $res_index = $db -> query("SELECT * FROM `pages` WHERE `url` = '$url' AND `status` = '1' LIMIT 1");

    if ($res_index -> num_rows) {

        $row_index = $res_index -> fetch_assoc();

        $page      = $row_index['page'];
        $type      = $row_index['type'];
        $template  = $row_index['template'];
        $id        = $row_index['id'];
        $title     = $row_index['title'];
        $desc      = htmlspecialchars($row_index['desc']);
        $keywords  = htmlspecialchars($row_index['keywords']);
        $h1        = $row_index['h1'];
        $text      = $row_index['text'];

        if (file_exists('templates/'.$template.'.tpl')) require_once 'templates/'.$template.'.tpl';
        else {
            @require_once './404.php';
            exit();
        }
    }
    else {
        // шаблон поиска - отдельной страницы в бд НЕТ!!!
        if (strpos($url, 'search') !== false) require_once 'templates/search.tpl';
        else {
            @require_once './404.php';
            exit();
        }
    }
}