<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
    date_default_timezone_set('PRC');
    $file = 'record.txt';
    if(!file_exists($file)){
        touch($file);
        $handle = fopen($file, "w");//读取二进制文件时，需要将第二个参数设置成'rb'
        fwrite($handle, time());
        fclose($handle);
        echo 0;
    }else{
        $handle = fopen($file, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        $contents = fread($handle, 20);
        fclose($handle);
        echo $contents;
        if(time() - $contents > 3600 * 24){
            $handle = fopen($file, "w");//读取二进制文件时，需要将第二个参数设置成'rb'
            fwrite($handle, time());
            fclose($handle);
        }
    }
?>