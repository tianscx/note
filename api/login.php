<?php
//data 0:成功  -1：缺少参数    -2：密码错误  -3：用户不存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["name"]) && isset($_POST["password"])){
        $name = $_POST["name"];
        $password = md5($_POST["password"]);
        $sql = "select * from user where name = '$name'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) == 0){
            $data['code'] = -3;
        }else{
            if($results[0]["status"] == 1){
                if($results[0]['password'] == $password){
                        $data['code'] = 0;
                        $data['data'] = $results[0];
                }else{
                    $data['code'] = -2;
                }
            }else{
                $data['code'] = -4;
            }

        }
    }else{
        $data['code'] = -1;
    }
    echo json_encode($data);
?>