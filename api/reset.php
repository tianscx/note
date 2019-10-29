<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST['id']) && isset($_POST['email']) && isset($_POST['password'])){
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $sql = "select * from user where id = '$id' && email = '$email'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) == 0){
            $data['data'] = -2;
            echo json_encode($data);
            return;
        }
        $sql = "UPDATE user SET password = '$password' WHERE id = '$id'";
        if ($conn->query($sql) == TRUE) {
            $data['data'] = 0;
        } else {
            $data['data'] = 1;
        }
    }else{
        $data['data'] = -1;
    }

    echo json_encode($data);
?>