<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

$query_ins_post_filters = '';
$icon_filter = ''; // Иконки фильтров на страницу products.php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

    if (!empty($_POST['filters'])) {

        $Filters_post = $_POST['filters'];

        $Filters_post_filter = array(); // Массив с названиями фильтров, в которых выбран хоть один параметр
        $Filters_post_param  = array(); // Массив с названиями выбранных параметров

        foreach ($Filters_post as $post_filter  => $Params_post) {

            $filter_prev = '';

            foreach ($Params_post as $post_param) {

                $Filters_post_filter[] = $post_filter; // Создаем массив названий фильтров, в которых выбран хоть один параметр
                $Filters_post_param[]  = $post_param; // Массив выбранных параметров

                if ($filter_prev == $post_filter) $query_ins_post_filters .= " OR ";
                elseif (count($Params_post) > 1)  $query_ins_post_filters .= " AND (";
                else                              $query_ins_post_filters .= " AND ";

                $query_ins_post_filters .= "`$post_filter` = '$post_param' ";
                $filter_prev = $post_filter;

                // Создаем переменную для размещения в блоке иконки фильтра на странице продукты
                if (!empty($post_param)) $icon_filter .= '<span class="icon_filter">'.$post_param.'<span class="popup_close_filter" param="'.$post_param.'">╳</span></span>';
            }
            if (count($Params_post) > 1) $query_ins_post_filters .= ')';
        }
    }

 //   print_r($Filters_post_filter);
 //   echo '<br>';
 //   print_r($Filters_post_param);
}

// Поля БД для фильтра
$Filters = array(
    //'Тип' => 'subcat',
    'Производитель' => 'manuf',
    'Материал' => 'mat',
    'Размер' => 'size'
);
// $filter - это название столбцов в БД по которым производится фильтровка
$filters_count = 0; // счетчик к-ва параметров в всех фильтрах

// выводим ли заголовок
foreach ($Filters as $filter_name => $filter) {

    $res_filter = $db -> query("SELECT DISTINCT `$filter` FROM `products` WHERE `cat` = '$page' AND `status` = '1' ORDER BY `stock`, `sort`");

    if ($res_filter -> num_rows && $res_filter -> num_rows >= 2) {
        $filters_count++;
    }
}
if ($filters_count > 0) {
    echo '
    <div class="left_filters">
        <div id="f"></div>
        <div class="left_filters_title"><i class="fa fa-check-square-o"></i>Подбор по параметрам</div>';
}



foreach ($Filters as $filter_name => $filter) {

    // Делаем выборку по конкретному фильтру из массива, заданного выше
    $res_filter = $db -> query("
        SELECT `$filter` 
        FROM ( 
            SELECT `$filter` 
            FROM `products` 
            WHERE `cat` = '$page' 
            AND `status` = '1' 
            ORDER BY `stock`, `sort` 
            ) as t 
        GROUP BY `$filter` 
        ORDER BY NULL
    ");
    if ($res_filter -> num_rows && $res_filter -> num_rows >= 2) {


        echo '
        <div class="left_filter">
                <p class="left_filter_title" filter="'.$filter.'">'.$filter_name.'</p>';
        $filter_print = ''; // переменнная для вывода всех фильтров. Прочее в конце



        while($row_filter = $res_filter -> fetch_assoc()) {

            $param = $row_filter[$filter];
            $name_param = !empty($param) ? $param : 'прочее';

            // Если группа имеет checked параметр
            $query_ins_post_filters_left = '';
            $plus = '';

            if (isset($Filters_post_filter)) {

                foreach ($Filters_post as $post_filter  => $Params_post) {

                    $filter_prev = '';

                    if ($filter != $post_filter) {

                        foreach ($Params_post as $post_param) {

                            if ($filter_prev == $post_filter) $query_ins_post_filters_left .= " OR ";
                            elseif (count($Params_post) > 1)  $query_ins_post_filters_left .= " AND (";
                            else                              $query_ins_post_filters_left .= " AND ";
                                                              $query_ins_post_filters_left .= "`$post_filter` = '$post_param' ";

                            $filter_prev = $post_filter;
                        }
                        if (count($Params_post) > 1) $query_ins_post_filters_left .= ')';
                    }
                    else $plus = '+';
                }
            }
            $res_count_param_left = $db -> query("
                SELECT COUNT(`$filter`)
                FROM `products`
                WHERE `cat` = '$page'
                AND `$filter` = '$param'
                AND `status` = '1'
                $query_ins_post_filters_left
                LIMIT 1
            ");
            // var_dump("
            //     SELECT COUNT(`$filter`)
            //     FROM `products`
            //     WHERE `cat` = '$page'
            //     AND `$filter` = '$param'
            //     AND `status` = '1'
            //     $query_ins_post_filters_left
            //     LIMIT 1
            // ");
            if ($res_count_param_left -> num_rows) {

                $row_count_param_left = $res_count_param_left -> fetch_row();
                $count_left = $row_count_param_left[0];

                if (!empty($count_left)) {

                    if ($param != '') { // если не прочее, то выводим сразу
                        $filter_print .= '<div class="left_filter_param"><input type="checkbox" param="'.$param.'"';

                        // Учитываем checked
                        if (isset($_POST['filters'][$filter]) && in_array($param,$_POST['filters'][$filter])) {
                            $filter_print .= ' checked ';
                            $plus='';
                        }
                        $filter_print .= '>'.$name_param.' <span class="small_letter">('.$plus.$count_left.')</span></div>';
                    }
                    // если прочее, то добавляем в конец
                    else {
                        $filter_print_end = '<div class="left_filter_param"><input type="checkbox" param="'.$param.'"';

                        // Учитываем checked
                        if (isset($_POST['filters'][$filter]) && in_array($param,$_POST['filters'][$filter])) {
                            $filter_print_end .= ' checked ';
                            $plus='';
                        }
                        $filter_print_end .= '>'.$name_param.' <span class="small_letter">('.$plus.$count_left.')</span></div>';
                    }
                }
            }
        }
        echo $filter_print.$filter_print_end;

        echo '</div>';
    }
}
echo '<div>';

?>