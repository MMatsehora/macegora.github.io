<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

// Корзина слева
$res_cart = $db -> query("
    SELECT   T2.product, T2.id, T1.id_product, T1.qt, T1.price, T1.trade_price, T1.if_trade_price
    FROM
        (SELECT `id_product` AS id_product, `qt` AS qt, `price` AS price, `trade_price` AS trade_price, `if_trade_price` AS if_trade_price
        FROM `cart`
        WHERE `cookie` = '$cookie'
        AND   `status` = '1'
        ORDER BY `id`) T1,

        (SELECT `id` AS id,`product` AS product
        FROM `products`) T2
    WHERE T2.id =  T1.id_product
");
if ($res_cart -> num_rows) {

    $cart_sum = '0';
    $cart_qt  = $res_cart -> num_rows;

    echo '
        <div class="popup_cart">
            <div class="popup_close">╳</div>

            <div id="popup_cart_block" cart_qt="'.$cart_qt.'">
                <h2>КОРЗИНА</h2>
                ';

    while ($row_cart = $res_cart -> fetch_assoc()) {

        $id_product     = $row_cart['id_product'];
        $product        = $row_cart['product'];
        $qt             = $row_cart['qt'];
        $price          = $row_cart['price'];
        $trade_price    = $row_cart['trade_price'];
        $if_trade_price = $row_cart['if_trade_price'];

        $product_price  = ($if_trade_price !=0 && $qt  >= $if_trade_price) ? $trade_price : $price;
        $product_sum    = $product_price*$qt;
        $cart_sum      += $product_sum;

    echo '   <div class="popup_cart_product_block" id_product="'.$id_product.'">';

                $res_foto = $db -> query("
                    SELECT `foto`,`alt`
                    FROM `fotos`
                    WHERE `product_id` = '$id_product'
                    ORDER BY `sort`
                    LIMIT 1"
                );
                if ($res_foto -> num_rows) {

                    $row_foto = $res_foto -> fetch_assoc();

                    echo '<img src="'.$dir.'/'.$id_product.'/'.$row_foto['foto'].'" alt="'.$row_foto['alt'].'" width="50" height="50" />';
                }
                echo '<div class="popup_cart_product">'.$product.'</div>
                        <div class="popup_cart_price_block">
                            <input class="popup_cart_input" type="text" title="qt" value="'.$qt.'" maxlength="12" name="qt">
                            x <span class="popup_cart_price" id_product="'.$id_product.'" price="'.$price.'" trade_price="'.$trade_price.'" if_trade_price="'.$if_trade_price.'">'.$product_price.'</span> грн. =<span class="popup_cart_sum">'.$product_sum.'</span>грн.<i class="fa fa-times del_pos" title="Удалить" product_sum="'.$product_sum.'"></i>
                        </div>
                </div>';
        }
        echo '
                <div class="popup_cart_sum">Итого: <span id="popup_cart_sum" class="ins_cart_sum">'.$cart_sum.'</span> грн. </div>

                <div class="popup_cart_button">
                    <button title="Продолжить покупки" type="submit" class="open_cart button_close"><i class="fa fa-cart-plus"></i>
    Продолжить покупки</button>
                    <button title="Оформить заказ" type="submit" class="make_order"><i class="fa fa-shopping-bag"></i>
    Оформить заказ</button>
                </div>

                <div id="popup_cart_checkout">
                    <h2>Контактные данные для заказа</h2>
                    <div class="checkout_note">* Имя и телефон - обязательные поля для заполнения.</div>

                    <div>
                        <label for="checkout_name">Имя*</label>
                        <input id="checkout_name" type="text" name="name" placeholder="Введите свое имя">

                        <label for="checkout_tel">Телефон*</label>
                        <input id="checkout_tel" type="text" name="tel" placeholder="(XXX) XXX-XX-XX">
                    </div>
                    <div class="div_checkout_adds">
                        <label for="checkout_adds">Адрес доставки**</label>
                        <textarea id="checkout_adds" name="adds" placeholder="Введите адрес доставка"></textarea>
                    </div>
                    <div class="button_make_checkout">
                        <em>** Мы перезвоним и уточним все вопросы по доставке</em>
                        <button title="Заказ подтверждаю" type="submit" class="btn btn-success btn-sale make_checkout"><i class="fa fa-check-square"></i> Заказ подтверждаю</button>
                    </div>
                </div>
            </div>
        </div>';
}
?>
<script>
$(function() {
    // Закрытие корзины
    $(".popup_close,.button_close").click(function(){
        $("#wrapper_popup_cart").fadeOut(200);
        $(".popup_cart").remove();
    });
    // Удаление одного товара
    $(".del_pos").click(function(){
        id_product = $(this).siblings('.popup_cart_price').attr("id_product");

        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: {
                op             : "del_pos",
                id_product     : id_product
            },
            success: function(data) {
                $(".popup_cart_product_block[id_product="+id_product+"]").remove();
                $("#calc_popup_cart").html(data);
            }
        });
    });
    // Пересчет корзины
    $(".popup_cart_input").keyup(function(e) {
        $('#loader').fadeIn(200);
        var qt = Number($(this).val());

        if (!(qt > 0)) {
            $(this).val('1');
            qt = 1;
        }

        var id_product      = $(this).siblings(".popup_cart_price").attr("id_product");
        var price           = $(this).siblings(".popup_cart_price").attr("price");
        var trade_price     = $(this).siblings(".popup_cart_price").attr("trade_price");
        var if_trade_price  = Number($(this).siblings(".popup_cart_price").attr("if_trade_price"));
        var old_product_sum = $(this).siblings(".popup_cart_sum").text();
        var old_cart_sum    = $("#popup_cart_sum").text();

        if (if_trade_price != 0 && qt >= if_trade_price) { price_new = trade_price; }
        else {price_new = price;}
        var product_sum = qt * price_new;
        var cart_sum    = old_cart_sum - old_product_sum + product_sum;

        $(this).siblings(".popup_cart_price").html(price_new);
        $(this).siblings(".popup_cart_sum").html(product_sum);

        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: {
                op             : "add_cart",
                price          : price,
                trade_price    : trade_price,
                if_trade_price : if_trade_price,
                sum            : product_sum,
                qt             : qt,
                Geo            : Geo,
                id_product     : id_product
            },
            success: function(data) {
                $(".ins_cart_sum, #popup_cart_sum").html(cart_sum);
                // console.log(data, ' cart.php');
                // $('#test').html($('#test').html() + '<br>' + data);
                $('#loader').fadeOut(200);
            }
        });
    });
    // разрешаем вводить только цифры
    jQuery.fn.ForceNumericOnly = function() {
        return this.each(function() {
            $(this).keydown(function(e) {
                var key = e.charCode || e.keyCode || 0;
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 46 ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };
    $(".popup_cart_input").ForceNumericOnly();
    // Оформление заказа
    $(".make_order").click(function() {
        // mask();
        $("input[name=tel]").mask("(999) 999-99-99");
        $("#popup_cart_checkout").toggle(200);

        $("#wrapper_left_cart").html();
        $(".ins_cart_qt").html();
        $(".ins_cart_sum").html();
    });
    // Проверка правильности заполнения полей
    $("#checkout_name,#checkout_tel").change(function() {
        if ($("#checkout_name").val() == "") { $("#checkout_name").css("background-color", "yellow"); }
        else {$("#checkout_name").css("background-color", "palegreen");}
        if ($("#checkout_tel").val() == "") { $("#checkout_tel").css("background-color", "yellow");}
        else {$("#checkout_tel").css("background-color", "palegreen");}
        if ($("#checkout_name").val() == "" || $("#checkout_tel").val() == "") {return;}
    });
    $(".make_checkout").click(function() {

        var name = $("#checkout_name").val();
        var tel  = $("#checkout_tel").val();
        var adds = $("#checkout_adds").val();
        var qt   = $("#popup_cart_block").attr("cart_qt");
        var sum  = $("#popup_cart_sum").text();

        if ($("#checkout_name").val() == "") { $("#checkout_name").css("background-color", "yellow"); }
        else {$("#checkout_name").css("background-color", "palegreen");}
        if ($("#checkout_tel").val() == "") { $("#checkout_tel").css("background-color", "yellow");}
        else {$("#checkout_tel").css("background-color", "palegreen");}
        if ($("#checkout_name").val() == "" || $("#checkout_tel").val() == "") {return;}

        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: {
                op   : 'checkout',
                Geo  : Geo,
                tel  : tel,
                name : name,
                adds : adds,
                qt   : qt,
                sum  : sum
            },
            success: function(data){
                $(".ins_cart_qt").html(0);
                // $(".ins_cart_sum").html(0);
                $("#popup_cart_checkout").html(data);
                $("#popup_cart_checkout").css('color','#EF0000');
                $("#popup_cart_block").attr("cart_qt",'');

                // закрываем корзину через время
                // setTimeout(function () {
                //     $('#wrapper_popup_cart').fadeOut(400);
                // }, 4000);

                // yaCounter26345961.reachGoal('FULLORDER');
                // ga('send', 'event', 'FULLORDER','clicked');
                // image = new Image(1,1);
                // image.src =  "http://www.googleadservices.com/pagead/conversion/966545121/?label=Dm6iCOyisFcQ4Z3xzAM&amp;guid=ON&amp;script=0";
            }
        });
    });
});
</script>