<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file (breadcrumb)' );

echo '
<div class="wrapper_breadcrumb">
    <div class="container">
        <div class="breadcrumb_cart">В корзине товара на сумму: <strong class="ins_cart_sum">'.$cart_sum.'</strong> грн.</div>

        <ul class="breadcrumb" id="breadcrumb">
            <li><a href="/">Главная</a></span></li>';

            $res_product = $db -> query("SELECT * FROM `products` WHERE `product` = '$page' AND `status` = '1' LIMIT 1");

            if ($res_product -> num_rows) {

                $row_product = $res_product -> fetch_assoc();

                $id_product     = $row_product['id'];
                $product        = $row_product['product'];
                $product_desc   = $row_product['product_desc'];
                $cat            = $row_product['cat'];
                $subcat         = $row_product['subcat'];
                $manuf          = $row_product['manuf'];
                $mat            = $row_product['mat'];
                $size           = $row_product['size'];
                $weight         = $row_product['weight'];
                $price          = $row_product['price'];
                $trade_price    = $row_product['trade_price'];
                $if_trade_price = $row_product['if_trade_price'];
                $stock          = $row_product['stock'];

                if (!empty($cat)) echo ' <i class="fa fa-angle-right"> </i> <li class="page_active"><a href="'.translit($cat).'">'.my_ucfirst($cat).'</a></li>';
                if (!empty($product))  echo ' <i class="fa fa-angle-right"> </i> <li class="page_active">'.my_ucfirst($product).'</li>';
            }
            else if (!empty($page)) echo ' <i class="fa fa-angle-right"> </i> <li class="page_active"><a href="'.translit($page).'">'.my_ucfirst($page).'</a></li>';

echo '
        </ul>
    </div>
</div>
';