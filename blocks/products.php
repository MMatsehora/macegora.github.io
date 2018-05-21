<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

if (!isset($query_ins_post_filters)) $query_ins_post_filters = '';

// Для пагинации ----- Начало
    $query_count = "
        SELECT COUNT(*)
        FROM `products`
        WHERE `cat` = '$page'
        AND `status` = '1'
        $query_ins_post_filters
    ";
    $res_count = $db -> query($query_count);

    if ($res_count -> num_rows) {

        $row_count = $res_count -> fetch_row();
        $count = $row_count[0];
    }
    if (isset($_POST['limit']) && $_POST['limit'] == 'all') {
        $limit = $count;
    }
    elseif (isset($_POST['limit'])) {

        $limit = 11 + $_POST['limit'];
        if ($limit > $count) $limit = $count;
    }
    else $limit = 11;
// Для пагинации ----- Конец

// сортировка товара
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if (isset($_POST['sort_product'])) $sort_product = $_POST['sort_product'];
    else $sort_product = 'sort';
}
else $sort_product = 'sort';

echo '
<section>
    <h1>'.$h1.'</h1>';

// if ($id != 175) {
// echo '<div class="sort_product">
//         <select>
//             <option value="sort"'; if ($sort_product == 'sort') echo ' selected="selected"'; echo '>По умолчанию</option>
//             <option value="price"'; if ($sort_product == 'price') echo ' selected="selected"'; echo '>По цене</option>
//             <option value="product"'; if ($sort_product == 'product') echo ' selected="selected"'; echo '>По алфавиту</option>
//         </select>
//     </div>';
// }

echo '<div class="products">';

// Блок иконок фильтров ---- Начало
    if (empty($query_ins_post_filters)) $query_ins_post_filters = '';
    else { // Здесь выводим иконки фильтров

        // Всего позиций в данной категории
        $res_count_all = $db -> query("
            SELECT COUNT(*)
            FROM `products`
            WHERE `cat` = '$page'
            AND `status` = '1'
        ");
        if ($res_count_all -> num_rows) {

            $row_count_all = $res_count_all -> fetch_row();
            $count_all = $row_count_all[0];
        }

        echo '<div class="wrapper_icon_filter"><span class="icon_filter_info">Подобрано '.$count.' из '.$count_all.' позиций</span>'.$icon_filter.'<span class="icon_filter icon_filter_reset">Сбросить</span></div>';
    }
// Блок иконок фильтров ---- Конец

    $query_products = "
        SELECT *
        FROM `products`
        WHERE `cat` = '$page' AND `status` = '1' $query_ins_post_filters
        ORDER BY `stock`, `$sort_product`
        LIMIT $limit
    ";

    $res_products = $db -> query($query_products);

        if ($res_products -> num_rows) {

             while ($row_products = $res_products -> fetch_assoc()) {

                $id_product     = $row_products['id'];
                $product        = $row_products['product'];
                $url            = $row_products['url'];
                $price          = $row_products['price'];
                $trade_price    = $row_products['trade_price'];
                $if_trade_price = $row_products['if_trade_price'];
                $stock          = $row_products['stock'];              
                
                echo '
                <div class="products_block">
                    <div class="products_block_img">';

                    $query_fotos = "
                        SELECT `foto`,`alt`
                        FROM `fotos`
                        WHERE `product_id` = '$id_product'
                        ORDER BY `sort`
                        LIMIT 1
                    ";
                    $res_fotos = $db -> query($query_fotos);

                    if ($res_fotos -> num_rows) {

                        $row_fotos = $res_fotos -> fetch_assoc();
                        $foto = $row_fotos['foto'];
                        $alt  = $row_fotos['alt'];

                        echo '<a href="/'.$url.'"><img src="'.$dir.'/'.$id_product.'/'.$foto.'" alt="'.$alt.'" width="250" height="200" /></a>';
                    }
               echo '</div>
                    <hr />
                    <h2>'.$product.'</h2>

                    <div class="products_block_wrapper_price">';
                    
                        // Если цену не проставили (она равно 0), но обозначено, что товар "В наличии", то вместо цены выводим надпись "Уточняйте")
                        if (empty($price)) echo ' <div class="products_block_price">Цену уточняйте</div>';
                        else               echo ' <div class="products_block_price">Цена: <strong>'.$price.'</strong> грн.</div>';                      
                        
                        if ($trade_price && $if_trade_price) echo '<div class="products_block_trade_price">Опт: <strong>'.$trade_price.'</strong> грн. при покупке от '.$if_trade_price.' шт.</div>';

                        if     (isset($stock) && $stock == 'В наличии') $ins_stock = ' green">'.$stock;
                        elseif (isset($stock) && $stock == 'Под заказ') $ins_stock = ' blue">'.$stock;
                        else                                            $ins_stock = ' red">Нет в наличии';
                        echo '
                        <div class="products_block_stock'.$ins_stock.'</div>

                        <div class="products_block_add_cart">
                            <span class="add_cart up"><i class="fa fa-plus"></i></span>
                            <span class="add_cart down"><i class="fa fa-minus"></i></span>
                            <input class="add_cart_input" type="text" title="qt" value="1" maxlength="12" name="qt" price='.$price.' if_trade_price='.$if_trade_price.' trade_price='.$trade_price.'>
                            <div class="add_cart_block_price"> х <span class="add_cart_price">'.$price.'</span> = <span class="add_cart_sum">'.$price.'</span>  грн.</div>
                            <button id_product="'.$id_product.'" class="btn btn-success btn_add_cart"'; if (!empty($stock) && $stock == 'Нет в наличии') echo ' disabled'; echo '>В корзину</button>
                            <a href="/'.$url.'"><button class="btn btn-info btn_more" id>Подробно</button></a>
                        </div>
                    </div>
                </div>';
            }
            // if ($limit < $count) {
            //     echo '<div class="products_block next_page"><span>Показано '.$limit.' из '.$count.'</span><p><span class="products_block_view_10" limit="'.$limit.'">Показать еще ';

            //     if ($count - $limit < 11) echo $count - $limit;
            //     else echo '11';

            //     echo '</span><br/><i class="fa fa-refresh fa-5x"></i></p><span class="products_block_view_all" limit="all">Показать все</span></div>';
            // }
            if ($limit < $count) {
                echo '<div class="products_block next_page"><span>Показано '.$limit.' из '.$count.'</span><p><span class="products_block_view_all" limit="all">Показать все</span><br/><i class="fa fa-refresh fa-5x"></i></p></div>';
            }
        }
echo '</div>
</section>';