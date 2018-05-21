$(function() {
    // главне меню
    $('#open_popup').click(function(){
        $('.left_popup').fadeIn();
         $('.left_filters').css({'display' : 'none'});
         if ($('.left_menu').css({'display' : 'none'})){
            $('.left_menu').css({'display' : 'block'})
         };
    });

    $('.close_left').click(function(){
        $('.left_popup').fadeOut();
    });

    $('i.bars').click(function () {
        $('.top_menu ul').slideToggle('fast');
    });

    // Аккордион для меню
	$('ul.tabs').delegate('li:not(.current)', 'click', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.nav').find('div.box').hide().eq($(this).index()).fadeIn(200);
	});
    // Красивый алерт по клику
    $('.ooo').click(function(){
        var alert = $(this).attr('title');
        swal(alert);
    });
    // Пагинация, следующая страница с учетом фильтров
    $('.products_block_view_10').click(function() {
        if ($(this).children('i').hasClass('fa-spin')) {$(this).children('i').removeClass('fa-spin');}
        else {
            $(this).children('i').addClass('fa-spin');

            var limit        = $(this).attr('limit');

            var Filter       = {};
            var url          = $('#breadcrumb').children('.page_active').children('a').attr('href');
            var sort_product = $('.sort_product select').val();

            $('input:checkbox:checked').each(function() {
                var filter = $(this).parent('div').siblings('p').attr('filter');
                if (Filter[filter] === undefined) { Filter[filter] = new Array(); }

                var param  = $(this).attr('param');
                Filter[filter].push(param);
            });
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    filters      : Filter,
                    limit        : limit,
                    url          : url,
                    sort_product : sort_product
                },
                success: function(data) {
                    $('body').html(data);
                }
            });
        }
    });
      $('.open_popup').click(function(){

        $('.popup_overlay').fadeToggle();

    });
    // Пагинация. Показать все
    $('.products_block_view_all').click(function() {
        if ($(this).children('i').hasClass('fa-spin')) {$(this).children('i').removeClass('fa-spin');}
        else {
            $(this).children('i').addClass('fa-spin');

            var limit        = $(this).attr('limit');

            var Filter       = {};
            var url          = $('#breadcrumb').children('.page_active').children('a').attr('href');
            var sort_product = $('.sort_product select').val();

            $('input:checkbox:checked').each(function() {
                var filter = $(this).parent('div').siblings('p').attr('filter');
                if (Filter[filter] === undefined) { Filter[filter] = new Array(); }

                var param  = $(this).attr('param');
                Filter[filter].push(param);
            });
            $.ajax({
                type: "POST",
                url: '',
                data: {
                    filters      : Filter,
                    limit        : limit,
                    url          : url,
                    sort_product : sort_product
                },
                success: function(data) {
                    $('body').html(data);
                }
            });
        }
    });
    // Закрыть иконку блока фильтра на странице продукты
    $(".popup_close_filter").click(function(){

        var checkbox = $(this).attr('param');
        $('input:checkbox:checked[param="'+checkbox+'"]').attr('checked', false);
        CheckFilter();
    });
    $(".icon_filter_reset").click(function(){

        $('input:checkbox:checked').attr('checked', false);
        CheckFilter();
    });
    // Выборка по фильтру
     $('.left_filter_param input').click(function(){
        CheckFilter();
    });
    // Функция передачи селектед чекбоксов на сервес
    function CheckFilter() {

        var Filter       = {};
        var url          = $('#breadcrumb').children('.page_active').children('a').attr('href');
        var sort_product = $('.sort_product select').val();

        $('input:checkbox:checked').each(function() {
            var filter = $(this).parent('div').siblings('p').attr('filter');
            if (Filter[filter] === undefined) { Filter[filter] = new Array(); }

            var param  = $(this).attr('param');
            Filter[filter].push(param);
        });
        $.ajax({
            type: "POST",
            url: '',
            data: {
                filters      : Filter,
                url          : url,
                sort_product : sort_product
            },
            success: function(data) { $('body').html(data); }
        });
    }
    // сортировка товара
    $('.sort_product select').change(function(){
        $('#loader').fadeIn(20);
        var sort_product = $(this).val();

        $.ajax({
            type: "POST",
            url: '',
            data: {
                sort_product : sort_product
            },
            success: function(data) { $('body').html(data); }
        });
    });
// Пересчет суммы за один товар при изменении количества ----------------- НАЧАЛО
    $('.add_cart_input').val('1');

    // Увеличение на единицу
    $(".add_cart").click(function(){
        var qt = Number($(this).nextAll('.add_cart_input').val());

        if ($(this).hasClass('up')) {qt++;}
        else if (qt > 1) { qt--;}

        var price          = Number($(this).nextAll('.add_cart_input').attr('price'));
        var if_trade_price = Number($(this).nextAll('.add_cart_input').attr('if_trade_price'));
        var trade_price    = Number($(this).nextAll('.add_cart_input').attr('trade_price'));

        if (if_trade_price != 0 && qt >= if_trade_price) { price = trade_price;  }

         sum = price*qt;

        $(this).nextAll('.add_cart_input').val(qt);
        $(this).nextAll('.add_cart_block_price').children('.add_cart_price').html(price);
        $(this).nextAll('.add_cart_block_price').children('.add_cart_sum').html(sum);
    });
    // Ввод при измении в данных в поле
    $(".add_cart_input").keyup(function(e) {
        var qt             = $(this).val();
        if (!(qt > 0)) {
            $(this).val('1');
            qt = 1;
        }

        var price          = $(this).attr('price');
        var if_trade_price = Number($(this).attr('if_trade_price'));
        var trade_price    = $(this).attr('trade_price');

        if (if_trade_price != 0 && qt >= if_trade_price) { var sum = trade_price*qt; price = trade_price; }
        else { var sum = price*qt; }
        $(this).next().children('.add_cart_sum').html(sum);
        $(this).next().children('.add_cart_price').html(price);
    });
// Пересчет суммы за один товар при изменении количества ----------------- КОНЕЦ

    // Добавление одной позиции в корзину
    $(".btn_add_cart").click(function(){
        $('#loader').fadeIn(200);
        var price          = Number($(this).prevAll('.add_cart_input').attr('price'));
        var if_trade_price = Number($(this).prevAll('.add_cart_input').attr('if_trade_price'));
        var trade_price    = Number($(this).prevAll('.add_cart_input').attr('trade_price'));
        var qt             = Number($(this).prevAll('.add_cart_input').val());
        var sum            = Number($(this).prevAll('.add_cart_block_price').children('.add_cart_sum').text());
        var id_product     = $(this).attr("id_product");

        $.ajax({
            type: "POST",
            url: "/ajax.php",
            data: {
                op             : "add_cart",
                price          : price,
                trade_price    : trade_price,
                if_trade_price : if_trade_price,
                sum            : sum,
                qt             : qt,
                Geo            : Geo,
                id_product     : id_product
            },
            success: function(data) {
                $("#wrapper_popup_cart").html(data);
                $('#loader').fadeOut(200);
            }
        });
    });
    // Открытие корзины
 
    function open_cart() {
        // Расчет расстояния окна от верха экрана с прокруткой
        $('.popup_cart').css({'top' : $(window).scrollTop()+10+'px'});
        $("#wrapper_popup_cart").fadeIn(200);
    }
    $('.open_cart').click(function(){
        var cart_qt = $('.ins_cart_qt').text();

        if (cart_qt != 0) {
            $.ajax({
                type: "POST",
                url: "/ajax.php",
                data: {
                    op             : "open_cart"
                },
                success: function(data) {
                    $("#wrapper_popup_cart").html(data);
                    open_cart();
                }
            });
        }
        else swal('Корзина пустая');
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
    $(".add_cart_input").ForceNumericOnly();


    // Открытие форм обратной связи
    $('.btn-feedback').click(function(){

        $('.popup_feedback').css({'display' : 'block'});
        $('.good_popup').css({'display' : 'none'});

        // Расчет расстояния окна от верха экрана с прокруткой
        $('.popup_feedback').not('.contact_feedback').css({'top' : $(window).scrollTop()+10+'px'});
        $("#wrapper_popup_feedback").fadeIn(200);

        // mask();
        $("input[name=tel]").mask("(999) 999-99-99");

        if ($(this).hasClass('btn-makecall')) { // если обратный звонок
            $('.is_popup .section_name, .is_popup .section_mail, .is_popup .section_message').css('display','none');
            $('.is_popup .section_phone').css('display','block');
            $('.is_popup .make_feedback').html('Заказать звонок');
        }
        else {
            $('.is_popup .section_phone').css('display','none');
            $('.is_popup .section_name, .is_popup .section_mail, .is_popup .section_message').css('display','block');
            $('.is_popup .make_feedback').html('Отправить письмо');
        }
        $('.popup_feedback input, .popup_feedback textarea').val('');
    });
    // Закрытие форм обратной связи
    $('body').on("click",".popup_feedback .popup_close, .good_popup .popup_close",function() {
        $("#wrapper_popup_feedback").fadeOut(100);
    });
    // отправка формы обратной связи
    $(".popup_feedback .make_feedback").click(function(){
        var correctColor = 'white';  // цвет поля при верных данных
        var wrongColor   = 'yellow'; // цвет поля при ошибочных данных
        var closestForm  = $(this).closest('.popup_feedback');
        var formname     = $(this).html();
        var phone        = closestForm.children('.section_phone').children('input').val();
        var name         = closestForm.children('.section_name').children('input').val();
        var mail         = closestForm.children('.section_mail').children('input').val();
        var message      = closestForm.children('.section_message').children('textarea').val();

        $('.popup_feedback input, .popup_feedback textarea').css('background-color', correctColor);

        if (formname == 'Заказать звонок' && phone == '') {
            closestForm.children('.section_phone').children('input').css('background-color', wrongColor);
            return false;
        }
        if (formname == 'Отправить письмо') {
            if (name == '') {
                closestForm.children('.section_name').children('input').css('background-color', wrongColor);
                return false;
            }
            if (mail == '') {
                closestForm.children('.section_mail').children('input').css('background-color', wrongColor);
                return false;
            }
            if (message == '') {
                closestForm.children('.section_message').children('textarea').css('background-color', wrongColor);
                return false;
            }
        }

        var ip      = Geo[0];
        var country = Geo[1];
        var city    = Geo[2];

        if((isValidMail(mail) && formname == 'Отправить письмо') || formname == 'Заказать звонок') {
            $.ajax({
                type: "POST",
                url:  "/functions/mail.php",
                data: {
                    formname : formname,
                    name     : name,
                    phone    : phone,
                    ip       : ip,
                    country  : country,
                    city     : city,
                    mail     : mail,
                    message  : message
                },
                success: function(data) {
                    if (data != '') swal(data);
                    else {

                        $('.popup_feedback').not('.contact_feedback').fadeOut(200);
                        $('.good_popup').css({'top' : $(window).scrollTop()+10+'px'}).fadeIn(200);

                        // Отслеживание конверий
                        // if (formname == 'Отправка письма') {
                        //     yaCounter26345961.reachGoal('FULLORDER');
                        //     ga('send', 'event', 'FULLORDER','clicked');
                        //     image = new Image(1,1);
                        //     image.src =  "http://www.googleadservices.com/pagead/conversion/966545121/?label=Dm6iCOyisFcQ4Z3xzAM&amp;guid=ON&amp;script=0";
                        // }
                        // if (formname == 'Oбратный звонок') {
                        //     yaCounter26345961.reachGoal('CALLBACK');
                        //     ga('send', 'event', 'CALLBACK','clicked');
                        //     image = new Image(1,1);
                        //     image.src =  "http://www.googleadservices.com/pagead/conversion/966545121/?label=zogSCLzR0lYQ4Z3xzAM&amp;guid=ON&amp;script=0";
                        // }

                        setTimeout(function(){
                            $("#wrapper_popup_feedback").fadeOut(100);
                        }, 3000);
                    }
                }
            });
        }
        else swal('Некорректный email!');

    });

    // запрет правой кнопки мыши
    // $("body").mousedown(function(e){
    //     if( e.button == 2 ) {
    //         $(this)[0].oncontextmenu = function() {return false;}
    //     }
    // });
});

function isValidMail(mail) { // правильность ввода мыла
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(mail);
}