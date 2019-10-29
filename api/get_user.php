<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $sql = "select * from user where email = '$email'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) > 0){
            $data["data"] = $results[0];
            $data['code'] = 0;
        }else{
            $data['code'] = -1;
        }

    }else{
        $data['code'] = 1;
    }

    echo json_encode($data);
?>