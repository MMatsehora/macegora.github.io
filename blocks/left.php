<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

echo '
<aside class="left_popup">
<div class="left">
    <span class="fa-layers fa-fw popup_close close_left" style="background: red">
    <i class="fa-inverse fas fa-times" data-fa-transform="shrink-6"></i>
    </span>
    <div class="left_menu" id="left_menu" url="'.$url.'">';

        $res_left = $db -> query("
            SELECT `type`
            FROM `types`
            WHERE `linking` = 'левое меню'
            ORDER BY `id`
        ");

        if ($res_left -> num_rows) {

            $i = '';
            $res_left1 = $db -> query("
                SELECT `type`
                FROM `types`
                WHERE `type` = '$type'
                AND `linking` = 'левое меню'
                ORDER BY `id`
            ");
            if ($res_left1 -> num_rows) { $i = 1; }

            echo '
            <div class="nav">
                <ul class="tabs">';
                    $n = 1;
                    while ($row_left = $res_left -> fetch_assoc()) { //Выводим заголовки левого меню
                        echo '<li'; if ((!$i && $n == 1) || $type == $row_left['type']) echo ' class="current" '; echo '><i class="fa fa-bars"></i>'.my_ucfirst($row_left['type']).'</li>';
                        $n++;
                   }
                echo '
                </ul>';

            $res_left -> data_seek(0); //Выводим пункты левого меню для каждой вкладки
            $n = 1;
            while ($row_left = $res_left -> fetch_assoc()) {

                $res_left_pages = $db -> query("
                    SELECT `page`,`url`
                    FROM `pages`
                    WHERE `type` = '$row_left[type]'
                    AND `status` = '1'
                    ORDER BY `sort`
                ");
                if ($res_left_pages -> num_rows) {

                    echo '<div class="box'; if ((!$i && $n == 1) || $type == $row_left['type']) echo ' visible'; echo '">
                            <ul>';
                        while ($row_left_pages = $res_left_pages -> fetch_assoc()) {

                            // к-во товаров в разделе
                            $res_count = $db -> query("
                                SELECT COUNT(*)
                                FROM `products`
                                WHERE `cat` = '$row_left_pages[page]'
                                AND `status` = '1'
                            ");
                            if ($row_count = $res_count -> fetch_row()) $qt = $row_count[0];
                            else $qt = 0;

                            if ($qt != 0 || $row_left['type'] == 'услуги') {
                                echo '<li'; if ((!empty($url) && $url == $row_left_pages['url'])|| (!empty($cat) && translit($cat) == $row_left_pages['url'])) echo ' class="active"'; echo '><a href="'.$row_left_pages['url'].'">'.my_ucfirst($row_left_pages['page']);

                                if ($row_left['type'] != 'услуги') echo ' ('.$qt.')';

                                echo '</a></li>';
                            }
                        }

                    echo '</ul>
                        </div>';
                    $n++;
                }
            }
        echo '</div>';
    }
    echo '</div>';


    if ($type == 'товары' && $id != 175) require_once 'blocks/left_filters.php'; // Если страница товара подключаем фильтры

    if (empty($banners_top) && $template == 'services') require_once 'blocks/left_slider_products.php';
    elseif (empty($banners_top)) require_once 'blocks/left_banners.php'; // Если вверху нет баннеров, то выводим их слева
    else require_once 'blocks/left_slider_products.php';
?>

</aside>