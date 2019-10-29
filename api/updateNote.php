<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    require('./encrypt.php');
    $data = [];
    if(isset($_POST['user_note_id']) && isset($_POST['label']) && isset($_POST['user_id'])){
        $user_note_id = $_POST['user_note_id'];
        $label = $_POST['label'];
        $user_id = $_POST['user_id'];
        $collect = $_POST['collect'];
        $time = $_POST['time'];
        $updateTime = $_POST['updateTime'];
        $content = encrypt($_POST['content'],'E','tianshicong');
        $status = $_POST['status'];
        $device_id = $_POST['device_id'];
        $islock = $_POST['islock'];
        $findsql = "select * from note where user_id = '$user_id' and user_note_id = '$user_note_id' and device_id = '$device_id'";
        $result = $conn->query($findsql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) == 0){
        //不存在，插入
            $sql = "INSERT INTO note (user_note_id, label, user_id, collect, time, updateTime, content, status, device_id, islock) VALUES ('$user_note_id', '$label', '$user_id', '$collect', '$time', '$updateTime', '$content', '$status', '$device_id', '$islock')";
        }else{
            if($results[0]['updateTime'] >= $updateTime){
                $sql = null;
            }else{
                $sql = "UPDATE note SET label = '$label', collect = '$collect', time = '$time', updateTime = '$updateTime', content = '$content', status = '$status', islock = '$islock' WHERE user_id = '$user_id' and user_note_id = '$user_note_id' and device_id = '$device_id'";
            }
        }
        if($sql){
            if ($conn->query($sql) == TRUE) {
                $data['code'] = 0;
            } else {
                $data['code'] = 1;
            }
        }else{
            $data['code'] = 0;
        }
        $data['sql'] = $sql;
        $data['postTime'] = $updateTime;
    }
    echo json_encode($data);
?>