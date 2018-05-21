<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

echo '
<footer>
    <div class="footer">
        <div class="top_footer">
            <div class="container">

                <div class="footer-ribbon">
                    <span>'.$name_company_ru.'</span>
                </div>

                <div class="top_footer_block1">
                    <div class="block-title">Меню</div>
                    <div class="block-content">
                        <ul class="links">';


                        if ($res_top_menu -> num_rows) {

                            $res_top_menu -> data_seek(0);

                             while ($row_top_menu = $res_top_menu -> fetch_assoc()) {

                                $page_top_menu = $row_top_menu['page'];
                                $url_top_menu  = empty($row_top_menu['url']) ? '/' : $row_top_menu['url'];

                                echo '<li><i class="fa fa-caret-right"></i><a href="'.$url_top_menu.'"'; if ($url == '$url_top_menu') echo ' class="active"'; echo'>'.$page_top_menu.'</a></li>';
                            }
                        }
                    echo '
                        </ul>
                    </div>
                </div>

                <div class="top_footer_block2">
                    <div class="block-title">Контактная информация</div>
                    <div class="block-content">
                        <ul class="links">
                            <li><i class="fa fa-map-marker"></i> Адрес:</strong><br /><span>'.$contact_city.', '.$contact_adress.'</span></li>
                            <li><i class="fa fa-clock-o"></i><strong> Режим работы:</strong><br /><span>'.$time_works .'</span></li>
                            <li><i class="fa fa-phone fa-white"></i><strong> Телефоны:</strong><br /><span>'.$contact_tel1.'</span><br /><span>'.$contact_tel2.'</span><br /><span>'.$contact_tel3.'</span></li>
                            
                            <li><i class="fa fa-envelope-o"></i><strong>E-mail:</strong><br /><span>'.$contact_email.'</span></li>
                        </ul>
                    </div>
                </div>

                <div class="top_footer_block3">
                    <div class="block-title">Товары</div>
                    <div class="block-content">
                        <ul class="links">';

                $res_footer = $db -> query("
                    SELECT `page`,`url`
                    FROM `pages`
                    WHERE `type` = 'товары'
                    AND `status` = '1'
                    ORDER BY `sort`
                ");
                if ($res_footer -> num_rows) {

                    while ($row_footer = $res_footer -> fetch_assoc()) {

                        $page_footer = $row_footer['page'];
                        $url_footer  = $row_footer['url'];

                        // к-во товаров
                        $res_count = $db -> query("
                            SELECT COUNT(*)
                            FROM `products`
                            WHERE `cat` = '$page_footer'
                        ");
                        if ($row_count = $res_count -> fetch_row()) $count = $row_count[0];
                        else $count = 0;

                        if ($count != 0) echo '<li><i class="fa fa-check-square-o"></i><a href="'.$url_footer.'">'.$page_footer.'</a></li>';
                    }
                }
            echo '
                        </ul>
                    </div>
                </div>

                <div class="top_footer_block4">
                    <div class="block-title">Услуги</div>
                    <div class="block-content">
                        <ul class="links">';

                $res_footer = $db -> query("
                    SELECT `page`,`url`
                    FROM `pages`
                    WHERE `type` = 'услуги'
                    AND `status` = '1'
                    ORDER BY `sort`
                ");
                if ($res_footer -> num_rows) {

                     while ($row_footer = $res_footer -> fetch_assoc()) {

                        $page_footer = $row_footer['page'];
                        $url_footer  = $row_footer['url'];

                        echo '<li><i class="fa fa-check-square-o"></i><a href="'.$url_footer.'">'.$page_footer.'</a></li>';
                    }
                }
            echo '
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="bottom_footer">
            <div class="container">
                <div class="copyright">©Copyright 2015 by Geotex.com.ua. All Rights Reserved.</div>
                <div class="social_block">
                    <div class="social"><a title="" href=""><i class="fab fa-facebook-f fa-2x"></i></a></div>
                    <div class="social"><a title="" href=""><i class="fab fa-google-plus-g fa-2x"></i></a></div>
                    <div class="social"><a title="" href=""><i class="fab fa-twitter fa-2x"></i></a></div>
                    <div class="social"><a title="" href=""><i class="fab fa-vk fa-2x"></i></a></div>
                </div>
                <div class="footer_logo"><a href="/" title="На главную"><img src="/img/logo.png" alt="Лого" width="100" height="48" /></a></div>
                <div class="i_cart"><img src="/img/visa_master.png" width="135" height="28"></div>
            </div>
        </div>
    </div>
</footer>';