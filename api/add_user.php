<?php
//    name 用户名
//    password 密码
//    type 1:超级管理员  0：普通用户
//    site_id 如果type为0，绑定工地id

//data 0:成功  -1：缺少参数    -2：用户名已存在  1：插入失败

    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["name"]) && isset($_POST["password"])){
        $name = $_POST["name"];
        $password = md5($_POST["password"]);
        $email = $_POST['email'];
        $sql = "select * from user where name = '$name'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) > 0){
            $data['code'] = -2;
        }else{
            $sql1 = "select * from user where email = '$email'";
            $result1 = $conn->query($sql1);
            $results1 = array();
            while ($row1 = $result1->fetch_assoc()) {
                $results1[] = $row1;
            }
            if(count($results1) > 0){
                $data['code'] = -3;
                echo json_encode($data);
                return;
            }else{
                $sql = "INSERT INTO user (name, password, email, status, lock) VALUES ('$name', '$password', '$email', 0, '')";
            }
            if ($conn->query($sql) == TRUE) {
                $data['code'] = 0;
            } else {
                $data['code'] = 1;
            }
        }
    }else{
        $data['code'] = -1;
    }
    echo json_encode($data);
?>