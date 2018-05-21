$(function() {
    // настройки
    var config = {
        'items'      : 3,    // к-во миниатюр в видимой области
        'margin'     : 4,    // расстояние между миниатюрами, px
        'scrollItem' : 1,    // к-во миниатюр, которые скроллятся за клик
        'fadeIn'     : 400,  // время появления изображения, мс
        'scrollTime' : 400   // время скролла миниатюр, мс
    };

    var thumbWrapperWidth = $('.slidershop_thumb_wrapper').width();         // ширина видимой части контейнера с миниатюрами
    var thumbWidth        = thumbWrapperWidth/config['items'];          // ширина миниатюры

    // определение ширины миниатюр
    $('.slidershop_thumb img').css({
        'width'       : thumbWidth-config['margin']+'px',
        'marginRight' : (config['margin']/2)+'px',
        'marginLeft'  : (config['margin']/2)+'px'
    })

    var countThumb        = $('.slidershop_thumb img').length;              // к-во миниатюр
    var currentOffset     = parseFloat($('.slidershop_thumb').css('left')); // текущая позиция
    var allWidth          = parseFloat($('.slidershop_thumb').width());     // общая ширина блока с миниатюрами

    // показывать ли стрелки на миниатюрах
    if (countThumb <= config['items']) $('.fa-thumb').css('display', 'none');

    // смена главного изображения при клике на миниатюру
    $('.slidershop_thumb img').click(function() {
        if (!$(this).hasClass('active')) { // not не срабатывает)
            $('.slidershop_thumb img').removeClass("active");
            $(this).addClass('active');
            var src   = $(this).attr("src");
            var alt   = $(this).attr("alt");
            var curId = $(this).attr('id_img');
            $(this).parent().parent().prev().children().fadeOut(200).attr("src", src).attr("alt", alt).attr("id_img", curId).fadeIn(config['fadeIn']);

            scrollThumb(curId);
        }
    });

    // сдвиг миниатюр при клике на стрелках
    $('.fa-thumb').click(function() {
        currentOffset     = parseFloat($('.slidershop_thumb').css('left')); // текущая позиция
        allWidth          = parseFloat($('.slidershop_thumb').width());     // общая ширина блока с миниатюрами

        if($(this).hasClass('fa-chevron-left')) { // сдвиг влево
            if (currentOffset < 0) $('.slidershop_thumb').stop(false, true).animate({left: currentOffset+thumbWidth*config['scrollItem']}, config['scrollTime']);

        }
        else if($(this).hasClass('fa-chevron-right')) { // сдвиг вправо
            if (Math.abs(currentOffset) + thumbWrapperWidth < allWidth) $('.slidershop_thumb').stop(false, true).animate({left: currentOffset-thumbWidth*config['scrollItem']}, config['scrollTime']);
        }
    });

    // изменение главного изображения при клике на стрелках
    $('.fa-main').click(function() {
        var curIdOld = $('.slideshop img').attr('id_img');

        if($(this).hasClass('fa-chevron-left')) { // сдвиг влево
            var newImg = $('.slidershop_thumb img.active').prev();
            if (newImg.length == 0) newImg = $('.slidershop_thumb img').last();
        }
        else if($(this).hasClass('fa-chevron-right')) { // сдвиг вправо
            var newImg = $('.slidershop_thumb img.active').next();
            if (newImg.length == 0) newImg = $('.slidershop_thumb img').first();
        }

        var src   = newImg.attr('src');
        var alt   = newImg.attr('alt');
        var curId = newImg.attr('id_img');

        scrollThumb(curId);

        $('.slideshop img').fadeOut(200).attr("src", src).attr("alt", alt).attr("id_img", curId).fadeIn(config['fadeIn']);
        $('.slidershop_thumb img').removeClass("active");
        newImg.addClass('active');
    });

    // функция для центрирования миниатюр
    function scrollThumb(id) {
        var maxId         = $('.slidershop_thumb img').length - 1; // максимальный порядковый номер
        var countImgLeft  = (id == 0) ? 0 : id; // к-во изображений слева
        var countImgRight = (id == maxId) ? 0 : maxId - id;

        if (countImgLeft == 0)       var leftOffset = 0;
        else if (countImgRight == 0) var leftOffset = -(allWidth-thumbWrapperWidth);
        else                         var leftOffset = -(thumbWidth*(countImgLeft - 1));

        $('.slidershop_thumb').stop(false, true).animate({left: leftOffset}, config['scrollTime']);
    }
});