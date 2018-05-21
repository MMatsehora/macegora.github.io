<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

function h($str) {
    $str = htmlspecialchars($str, ENT_QUOTES);
    return $str;
}
function i($int) {
    $int = intval($int);
    return $int;
}
function convDate($date) {
    $date = explode("-", $date);
    $date = $date[2].'.'.$date[1].'.'.$date[0];
    return $date;
}