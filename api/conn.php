<?php
    $path = dirname(__FILE__);
    $config = require($path . '/db_config.php');
    // 创建连接
    $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbName']);
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    mysqli_query($conn, "set names utf8");
//    else{
//        echo '成功';
//    }
?>