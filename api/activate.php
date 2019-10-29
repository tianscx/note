<?php
//data 0:成功  -1：缺少参数    -2：密码错误  -3：用户不存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_GET["user"])){
        $user = $_GET["user"];
        $sql = "UPDATE user SET status = 1 WHERE name = '$user'";
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