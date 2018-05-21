<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file (db)' );

@$db = new mysqli($db_host,$db_user,$db_pass,$db_name);
if (mysqli_connect_errno()) {
    header("HTTP/1.0 404 Not Found");
    echo 'DB is not available, try again later';
    exit();
}
else $db -> query('SET NAMES '.$db_kod);

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

$res_config = $db -> query("SELECT * FROM `statant_config`");
if ($res_config -> num_rows) {

    while ($row_config = $res_config ->fetch_assoc()) {

        $name = $row_config['name'];
        $vol  = $row_config['val'];

        $$name = $vol;
    }
}