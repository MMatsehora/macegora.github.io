<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

echo '
<article>
    <h1>'.$h1.'</h1>
    <div class="content_text" data-id="'.$id.'">'.$text;

// на странице контактов добавляем форму
if ($url == 'kontakty') {

    echo '<div class="popup_feedback contact_feedback" data-id="'.$id.'">
        <div class="contact_feedback_title">Написать письмо</div>

        <div class="section_name">
            <label class="label_name">Введите имя</label>
            <input type="text" name="" id="" placeholder="Имя" autocomplete="off" />
        </div>

        <div class="section_phone" style="display: none;">
            <label class="label_phone">Введите номер телефона</label>
            <input type="text" name="tel" id="" placeholder="(xxx) xxx-xx-xx" autocomplete="off" />
        </div>

        <div class="section_mail">
            <label class="label_mail">Введите e-mail</label>
            <input type="text" name="" id="" placeholder="E-mail" autocomplete="off" />
        </div>

        <div class="section_message">
            <label class="label_message">Введите сообщение</label>
            <textarea name="" id="" placeholder="Сообщение"></textarea>
        </div>

        <button class="make_feedback">Отправить письмо</button>
    </div>';
}


echo '</div>
</article>
';