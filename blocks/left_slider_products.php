<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' ); ?>


<section>
    <div class="left_slider">
        <div class="carousel1 slide">
<?php

    $res_left_slider = $db -> query("
        SELECT *
        FROM `products`
        WHERE `stock` = 'В наличии'
        AND `status` = '1'
        GROUP BY `cat`
        ORDER BY `sort`
    ");
    if ($res_left_slider -> num_rows) {

        echo '<div class="carousel-inner">';

        while ($row_left_slider = $res_left_slider -> fetch_assoc()) {

            $id_product_left_slider = $row_left_slider['id'];
            $cat_left_slider        = $row_left_slider['cat'];
            $product_left_slider    = $row_left_slider['product'];
            $url_left_slider        = $row_left_slider['url'];
            $price_left_slider      = $row_left_slider['price'];

            echo '
            <div class="left_slider_product_block item'; if (empty($ii)) echo ' active'; echo '">
                <div class="left_slider_title">'.my_ucfirst($cat_left_slider).'</div>

                <h2>'.$product_left_slider.'</h2>

                <div class="left_slider_products_block_price">'.$price_left_slider.' грн.</div>
                <div class="left_slider_products_block_img">';

                $query_fotos_left_slider = "
                    SELECT `foto`,`alt`
                    FROM `fotos`
                    WHERE `product_id` = '$id_product_left_slider'
                    ORDER BY `sort`
                    LIMIT 1
                ";
                $res_fotos_left_slider = $db -> query($query_fotos_left_slider);

                if ($res_fotos_left_slider -> num_rows) {

                    $row_fotos_left_slider = $res_fotos_left_slider -> fetch_assoc();
                    $foto_left_slider = $row_fotos_left_slider['foto'];
                    $alt_left_slider  = $row_fotos_left_slider['alt'];

                    echo '<a href="/'.$url_left_slider.'"><img src="'.$dir.'/'.$id_product_left_slider.'/'.$foto_left_slider.'" alt="'.$alt_left_slider.'" width="200" height="160" /></a>';
                }
           echo '</div>
           </div>';
           $ii = 1;
         }
         echo '</div>';
    }
?>
        </div>
    </div>
</section>
<script defer="defer" src="js/left_slider.js"></script>