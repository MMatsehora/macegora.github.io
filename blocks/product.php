<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

echo '
<script defer="defer" src="js/slidershop.js"></script>

<section>
    <div class="product">

        <h1>'.$product.'</h1>
        <div class="wrapper_slidershop">';

    	$res_fotos = $db -> query("
            SELECT `foto`, `alt`
            FROM `fotos`
            WHERE `product_id` = '$id_product'
            ORDER by `sort`
        ");
    	if ($res_fotos -> num_rows) {

            $row_fotos  = $res_fotos -> fetch_assoc();

            $foto = $row_fotos['foto'];
            $alt  = $row_fotos['alt'];

            $path = $dir.'/'.$id_product;

            echo '
        	<div class="slidershop">
        		<i class="fa fa-chevron-left fa-thumb"></i>
        		<i class="fa fa-chevron-right fa-thumb"></i>
        		<i class="fa fa-chevron-left fa-main"></i>
        		<i class="fa fa-chevron-right fa-main"></i>
                <div class="slideshop">
                    <img src="'.$path.'/'.$foto.'" alt="'.$alt.'" id_img="0">
                </div>

        		<div class="slidershop_thumb_wrapper">
        			<div class="slidershop_thumb">';

                    $res_fotos -> data_seek(0);

    				$count = 0;

    				while ($row_fotos = $res_fotos -> fetch_assoc()) {

    					$foto = $row_fotos['foto'];
    					$alt  = $row_fotos['alt'];

    					echo '<img src="'.$path.'/'.$foto.'" class="';
    					if ($count == 0) echo 'active';
    					echo '" alt="'.$alt.'" id_img="'.$count.'">';

    					$count++;
    				}
            echo '
    			</div>
    		</div>
      </div>';
      }
      echo '
    	</div>

        <div class="product_block_wrapper">';
            if (!empty($product_desc)) echo '<div class="product_block_desc">'.$product_desc.'</div>';
            $Tab = array();

            if (!empty($manuf))  $Tab['Производитель']     = $manuf;
            if (!empty($mat))    $Tab['Защитное покрытие'] = $mat;
            if (!empty($size))   $Tab['Размеры, м.']       = $size;
            if (!empty($weight)) $Tab['Масса, кг.']        = $weight;

            if (!empty($Tab)) {
                echo '
                 <div class="product_params">
                    <ul>';

                    foreach($Tab as $key => $value) echo '<li><strong>'.$key.'</strong>: '.$value.'</li>';

                 echo '</ul>
                 </div>';
            }          
            // Если цену не проставили (она равно 0), но обозначено, что товар "В наличии", то вместо цены выводим надпись "Уточняйте")
            if (empty($price)) echo ' <div class="product_block_price" style="font-size:24px;">Цену уточняйте</div>';
            else               echo ' <div class="product_block_price">'.$price.' грн.</div>';  

            if ($trade_price && $if_trade_price) echo '<div class="product_block_trade_price">Оптовая цена: <strong>'.$trade_price.'</strong> грн. при покупке от '.$if_trade_price.' шт.</div>';

            if (isset($stock) && $stock == 'В наличии') $ins_stock = ' green">'.$stock;
            elseif (isset($stock) && $stock == 'Под заказ') $ins_stock = ' blue">'.$stock;
            else $ins_stock = ' red">Нет в наличии';
            echo '
            <div class="product_block_stock'.$ins_stock.'</div>

            <div class="product_block_add_cart">
                <span class="add_cart up"><i class="fa fa-plus"></i></span>
                <span class="add_cart down"><i class="fa fa-minus"></i></span>
                <input class="add_cart_input" type="text" title="qt" value="1" maxlength="12" name="qt" price='.$price.' if_trade_price='.$if_trade_price.' trade_price='.$trade_price.'>
                <div class="add_cart_block_price"> х <span class="add_cart_price">'.$price.'</span> = <span class="add_cart_sum">'.$price.'</span>  грн.</div>
                <button id_product="'.$id_product.'" class="btn btn-success btn_add_cart"'; if (!empty($stock) && $stock == 'Нет в наличии') echo ' disabled'; echo '>В корзину</button>
            </div>
        </div>';
echo '
    </div>';
if ($text != '') echo '<div class="content_text">'.$text.'</div>';
echo '</section>';
