<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );


echo '
<section>
    <h1>Результаты поиска</h1>
    <div class="products">';

	if (isset($_GET['search']) && $_GET['search'] != '') {
	   
       
		$search = $db -> real_escape_string(st($_GET['search']));

		if (strlen($search) <= 3) echo'<h2>Запрос слишком короткий - менее 4 символов!</h2>';
		else {

            // $search[mb_strlen($search)-1]=NULL; 		  
            $str = mb_substr($search,0,-2);
            
			// Всего позиций в данной категории
	        $res_count = $db -> query("
	            SELECT COUNT(*)
	            FROM `products`
	            WHERE `product` LIKE '%$str%'
                	            
	            AND `status` = '1'
	        ");
            //OR `product_desc` LIKE '%$str%')
			// $res_count = $db -> query("
	  //           SELECT COUNT(*),
	  //           MATCH `product`, `product_desc`
	  //           AGAINST('$search*' IN BOOLEAN MODE) as relev
	  //           FROM `products`
	  //           WHERE MATCH `product`, `product_desc`
	  //           AGAINST ('$search*' IN BOOLEAN MODE)>0
	  //       ");

	        // Для пагинации ----- Начало
     		// $count = $res_search -> num_rows;
     		if ($res_count -> num_rows) {
		        $row_count = $res_count -> fetch_row();
		        $count = $row_count[0];
		    }
     		if (isset($_POST['limit']) && $_POST['limit'] == 'all') $limit = $count;
		    elseif (isset($_POST['limit'])) {
		        $limit = 11 + $_POST['limit'];
		        if ($limit > $count) $limit = $count;
		    }
		    else $limit = 11;
		    // Для пагинации ----- Конец

			$res_search = $db -> query("
	            SELECT *
	            FROM `products`
	            WHERE `product` LIKE '%$str%'
	            
	            AND `status` = '1'
	            ORDER BY `stock`, `sort`
        		LIMIT $limit
	        ");
            // OR `product_desc` LIKE '%$str%')
		    // $res_search = $db -> query("
	     //        SELECT *,
	     //        MATCH `product`, `product_desc`
	     //        AGAINST('$search*' IN BOOLEAN MODE) as relev
	     //        FROM `products`
	     //        WHERE MATCH `product`, `product_desc`
	     //        AGAINST ('$search*' IN BOOLEAN MODE)>0
	     //        ORDER BY relev
      //   		LIMIT $limit
	     //    ");
	        if (!$res_search -> num_rows) echo '<h2>По слову "'.$search.'" ничего не найдено!</h2>';
         	else {

			    while ($row_search = $res_search -> fetch_assoc()) {
			    	$id_product     = $row_search['id'];
	                $product        = $row_search['product'];
	                $url            = $row_search['url'];
	                $price          = $row_search['price'];
	                $trade_price    = $row_search['trade_price'];
	                $if_trade_price = $row_search['if_trade_price'];
	                $stock          = $row_search['stock'];

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
			 //    if ($limit < $count) {
			 //    	echo '<div class="products_block next_page"><span>Показано '.$limit.' из '.$count.'</span><p><span class="products_block_view_10" limit="'.$limit.'">Показать еще ';

				//     if ($count - $limit < 11) echo $count - $limit;
				//     else echo '11';

				//     echo '</span><br/><i class="fa fa-refresh fa-5x"></i></p><span class="products_block_view_all" limit="all">Показать все</span></div>';
				// }
				if ($limit < $count) {
	                echo '<div class="products_block next_page"><span>Показано '.$limit.' из '.$count.'</span><p><span class="products_block_view_all" limit="all">Показать все</span><br/><i class="fa fa-refresh fa-5x"></i></p></div>';
	            }

         	}
		}
	}
	else {
		echo '<h2>По текущему запросу ничего не найдено!</h2>';
	}

echo '
	</div>
</section>';