<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

// Подсчет корзины (данные используеются во всех корзинах)
$cart_qt = 0;  //Количество позиций в корзине
$cart_sum = 0; //Стоимость всех позиций в корзине

$res_count_cart = $db -> query("
    SELECT `qt`,`price`,`trade_price`,`if_trade_price`
    FROM `cart`
    WHERE `cookie` = '$cookie'
    AND `status` = '1'
");
if ($res_count_cart -> num_rows) {

    $cart_qt  = $res_count_cart -> num_rows;

    while ($row_count_cart = $res_count_cart -> fetch_assoc()) {

        $qt             = $row_count_cart['qt'];
        $price          = $row_count_cart['price'];
        $trade_price    = $row_count_cart['trade_price'];
        $if_trade_price = $row_count_cart['if_trade_price'];

        $product_price  = ($if_trade_price !=0 && $qt  >= $if_trade_price) ? $trade_price : $price;

        $cart_sum += $product_price*$qt;
    }
}
// Подстановка в title и description данных со страницы, если они не пропасаны
if (!empty($template) && $template == 'product') {

    if (empty($title)) $title = $page;
    if (empty($desc)) {

        $res_seo = $db -> query("SELECT `product_desc` FROM `products` WHERE `product` = '$page' AND `status` = '1' LIMIT 1");

        if ($res_seo -> num_rows) {
            $row_seo = $res_seo -> fetch_assoc();
            $desc  = $row_seo['product_desc'];
        }
    }
}
$search_val = (isset($_GET['search'])) ? $_GET['search'] : '';

echo '<!DOCTYPE html>
<html lang="ru">
    <head>
    <meta charset="utf-8" />
    <title>'.$title.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="yandex-verification" content="3d91fb3b94698c9b" />
    <meta name="google" content="notranslate" />
	<meta name="keywords" content="'.$keywords.'" />
	<meta name="description" content="'.$desc.'" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
';

if (!empty($name_domen)) {

        $canonical_page =  !empty($url) ? $name_domen.'/'.$url : $name_domen;
        echo '<link rel="canonical" href="'.$canonical_page.'" />';
    }

echo '
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/styles.css"  />
    
    <link rel="stylesheet" href="/sweet-alert/sweet-alert.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/jPages.css">

    <!-- <script defer="defer" src="/js/jquery-latest.min.js"></script> -->
    <script defer="defer" src="/js/jquery-1.7.2.min.js"></script>
    <script defer="defer" src="/js/bootstrap.min.js"></script>
    <script defer="defer" src="/js/bd.ui.js"></script>
    <!-- <script defer="defer" src="/js/jquery.maskedinput.min.js"></script> -->
    <script defer="defer" src="/js/jquery.maskedinput-1.3.min.js"></script>
    <script defer="defer" src="/sweet-alert/sweet-alert.min.js"></script>
    <script defer="defer" src="/js/jPages.min.js"></script>
    <script defer="defer" src="/js/my.js"></script>

</head>
<body>
<div id="loader"><img src="/img/ajax-loader.gif" alt="Loading, Loading!" /></div>
<header>
    <nav>
        <div class="top_menu">
            <div class="container">
                <div class="header_logo_small"><a href="/" title="На главную"><img src="/img/logo.png" alt="Лого" width="220" height="99" /></a></div>
                <ul>';

                $query_top_menu = "
                    SELECT `page`,`url`
                    FROM `pages`
                    WHERE `type` = 'топ-меню'
                    AND `status` = '1'
                    ORDER BY `sort`
                ";
                $res_top_menu = $db -> query($query_top_menu);

                if ($res_top_menu -> num_rows) {

                     while ($row_top_menu = $res_top_menu -> fetch_assoc()) {

                        $page_top_menu = $row_top_menu['page'];
                        $url_top_menu  = empty($row_top_menu['url']) ? '/' : $row_top_menu['url'];

                        echo '<li><a href="'.$url_top_menu.'"'; if ($url == '$url_top_menu') echo ' class="active"'; echo'>'.$page_top_menu.'</a></li>';
                    }
                }
echo '
                </ul>
                <p class="top_menu_title">'; if(!empty($name_company_ru)) echo $name_company_ru; echo '<span><i class="fa fa-clock-o"></i>&nbsp;'; if(!empty($time_works)) echo $time_works; echo '</span></p>
            </div>
            </div>
        </div>
    </nav>

    <div class="header">
        <div class="container">
            <div class="header_top_menu">
            <i class="fa fa-bars bars"></i>
            <div class="header_categories">
                <div class="categories open_popup" id="open_popup">Товары | Услуги</div>
            </div>
            <div class="header_logo"><a href="/" title="На главную"><img src="/img/logo.png" alt="Лого" width="220" height="99" /></a></div>
            <div class="header_cart open_cart">
                <i class="fa fa-shopping-cart fa-5x"></i>
                <div class="header_cart_title">Корзина</div>
                <div id="header_cart" cookie="'.$cookie.'" cart_qt="'.$cart_qt.'" cart_sum="'.$cart_sum.'">
                    <span id="big_number_cart_qt" class="ins_cart_qt">'; if (!empty($cart_qt)) echo $cart_qt; echo '</span>
                </div>
            </div>
            <div class="clear"></div>
            </div>
            <div class="header_title title_mobile">'; if(!empty($header_title1)) echo $header_title1; echo '<br />'; if(!empty($header_title2)) echo $header_title2; echo '</div>
            <div class="header_contacts contacts_1">
                <p><a href="tel:+0636811145"><img class="icon_phone" src="/img/lifecell-16x16.png" alt="лайф" width="16" height="16">'; if(!empty($contact_tel3)) echo $contact_tel3; echo '</a></p>                
                <p><i class="fa fa-envelope-o"></i>&nbsp;&nbsp;'; if(!empty($contact_email)) echo $contact_email; echo '</p>
                <p><button onclick="" class="button btn-makemessage btn-feedback" title="Написать письмо" type="button"><i class="fa fa-envelope"></i>&nbsp;
 Написать письмо</button></p>
            </div>
            
            <div class="header_contacts contacts_2">
                <p><a href="tel:+0680557011"><img class="icon_phone" src="/img/kyivstar-16x16.png" alt="киевстар" width="16" height="16">'; if(!empty($contact_tel1)) echo $contact_tel1; echo '</a></p>
                <p><a href="tel:+0991791187"><img class="icon_phone" src="/img/vodofon-16x16.png" alt="водофон" width="16" height="16">'; if(!empty($contact_tel2)) echo $contact_tel2; echo '</a></p>                
                <p><button onclick="" class="button btn-makecall btn-feedback" title="Заказать звонок" type="button"><i class="fa fa-phone-square"></i>&nbsp;
 Заказать звонок</button></p>
            </div>

            <div class="search-area">
                <div class="header_title">'; if(!empty($header_title1)) echo $header_title1; echo '<br />'; if(!empty($header_title2)) echo $header_title2; echo '</div>
                <form action="/">
                    <input type="text" name="search" id="search" placeholder="Поиск по товарам..." autocomplete="off" value="'.$search_val.'" />
                    <button title="Поиск" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</header>';

echo '
    <div id="wrapper_popup_feedback">
        <div class="popup_feedback is_popup">
            <div class="popup_close">╳</div>

            <div class="section_phone">
                <label class="label_phone">Введите номер телефона</label>
                <input type="text" name="tel" id="" placeholder="(XXX) XXX-XX-XX" autocomplete="off" />
            </div>

            <div class="section_name">
                <label class="label_name">Введите имя</label>
                <input type="text" name="" id="" placeholder="Имя" autocomplete="off" />
            </div>

            <div class="section_mail">
                <label class="label_mail">Введите e-mail</label>
                <input type="text" name="" id="" placeholder="E-mail" autocomplete="off" />
            </div>

            <div class="section_message">
                <label class="label_message">Введите сообщение</label>
                <textarea name="" id="" placeholder="Сообщение"></textarea>
            </div>

            <button class="make_feedback">Заказать звонок</button>
        </div>
        <div class="good_popup">
            Ваша заявка принята.<br>Мы с Вами свяжемся.
            <div class="popup_close">╳</div>
        </div>
    </div>
   ';










