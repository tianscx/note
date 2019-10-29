<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    require('./encrypt.php');
    $data = [];
    if(isset($_POST['user_id'])){
        $user_id = $_POST['user_id'];
        $sql = "select * from note where user_id = '$user_id'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $row['content'] = encrypt($row['content'],'D','tianshicong')=="false&&"?$row['content']:encrypt($row['content'],'D','tianshicong');
            $results[] = $row;
        }
        $data["data"] = $results;
        $data['code'] = 0;
    }else{
        $data['code'] = -1;
    }

    echo json_encode($data);
?>