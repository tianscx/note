<?php
    $data = [];
    $conn = mysqli_connect('localhost', 'id1212780_myron_tian', 'tianshicong', 'id1212780_test');
    $conn1 = mysqli_connect('106.13.66.238', 'tiansc', 'tianshicong', 'note');
    mysqli_query($conn, "set names utf8");
    mysqli_query($conn1, "set names utf8");
    $deleteSql = "truncate TABLE note_note";
    var_dump($conn->query($deleteSql));
    $sql = "select * from note";
    $result = $conn1->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $data["note"] = $results;
    var_dump(count($data['note']));
    for($i = 0; $i < count($data["note"]); $i++){
        $aNote = $data["note"][$i];
//        var_dump($aNote);
        $noteSql = "INSERT INTO note_note (user_note_id, label, user_id, collect, time, updateTime, content, status, device_id,islock) VALUES ('$aNote[user_note_id]','$aNote[label]','$aNote[user_id]','$aNote[collect]','$aNote[time]','$aNote[updateTime]','$aNote[content]','$aNote[status]','$aNote[device_id]','$aNote[islock]')";
        $conn->query($noteSql);
    }

    $delUser = "truncate TABLE note_user";
    var_dump($conn->query($delUser));
    $userSql = "select * from user";
    $resultUser = $conn1->query($userSql);
    $resultsUser = array();
    while ($row1 = $resultUser->fetch_assoc()) {
        $resultsUser[] = $row1;
    }
    $data["user"] = $resultsUser;
    var_dump(count($data['user']));
    for($i = 0; $i < count($data["user"]); $i++){
        $user = $data["user"][$i];
//        var_dump($aNote);
        $insertUser = "INSERT INTO note_user (name, password, status, email, label_arr,safe_password) VALUES ('$user[name]','$user[password]','$user[status]','$user[email]','$user[label_arr]','$user[safe_password]')";
        $conn->query($insertUser);
    }
?>




