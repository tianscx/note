<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    require('./encrypt.php');
    $data = [];
    if(isset($_POST['text'])){
        $data['res'] = encrypt($_POST['text'],'D','tianshicong');
        $data['code'] = 0;
    }
    echo json_encode($data);
?>