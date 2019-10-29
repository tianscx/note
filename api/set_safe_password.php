<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST['safe_password']) && isset($_POST['user_id'])){
        $safe_password = $_POST['safe_password'];
        $user_id = $_POST['user_id'];
        $sql = "UPDATE user SET safe_password = '$safe_password' WHERE id = '$user_id'";
        if ($conn->query($sql) == TRUE) {
            $data['code'] = 0;
        } else {
            $data['code'] = 1;
        }
    }else{
        $data['code'] = -1;
    }
    echo json_encode($data);
?>