$(function() {



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
                $('#popup_cart_ins').html(data);
            }
        });
    });




// Корзина ------------------------ НАЧАЛО












// Обормление заказа
$(".make_order").click(function() {
    mask();
    $("input[name=tel]").mask("(999) 999-99-99");
    $("#popup_cart_checkout").toggle(200);

    $("#wrapper_left_cart").html();
    $(".ins_cart_qt").html();
    $("#popup_cart_block").attr("cart_qt",'');
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
            $("#wrapper_left_cart").html();
            $(".ins_cart_qt").html();
            $("#popup_cart_block").attr("cart_qt",'');
            $(".ins_cart_sum").html();
            $("#popup_cart_checkout").html(data);
        }
    });
});

// Пересчет корзины
$(".popup_cart_input").keyup(function(e) {

      var qt              = Number($(this).val());
      var id_product      = $(this).siblings(".popup_cart_price").attr("id_product");
      var price           = $(this).siblings(".popup_cart_price").attr("price");
      var trade_price     = $(this).siblings(".popup_cart_price").attr("trade_price");
      var if_trade_price  = Number($(this).siblings(".popup_cart_price").attr("if_trade_price"));
      var old_product_sum = $(this).siblings(".popup_cart_sum").text();
      var old_cart_sum    = $("#popup_cart_sum").text();

      if (qt >= if_trade_price) { price_new = trade_price; }
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
            $("#wrapper_left_cart").html(data);
            $(".ins_cart_sum, #popup_cart_sum").html(cart_sum);
        }
    });
});
// Корзина ------------------------------- КОНЕЦ
});