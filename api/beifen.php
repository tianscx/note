<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type:text/html;charset=utf-8");
$path = dirname(__FILE__);
$config = require($path . '/db_config.php');
$filePathName = '../backup/note_backup'. date("Y-m-d~h-i-sa") .'.sql';
//echo $filePathName;
//$filePathName = 'http://myrontian.000webhostapp.com/zulin/zulin_backup.sql';
if(file_exists($filePathName)){
    unlink($filePathName);
}
if(!function_exists('mysql_connect')){
    function mysql_connect($dbhost, $dbuser, $dbpass){
        global $dbport;
        global $dbname;
        global $mysqli;
        $mysqli = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        return $mysqli;
        }
    function mysql_select_db($dbname){
        global $mysqli;
        return mysqli_select_db($mysqli,$dbname);
        }
    function mysql_fetch_array($result){
        return mysqli_fetch_array($result);
        }
    function mysql_fetch_assoc($result){
        return mysqli_fetch_assoc($result);
        }
    function mysql_fetch_row($result){
        return mysqli_fetch_row($result);
        }
    function mysql_query($query){
        global $mysqli;
        return mysqli_query($mysqli,$query);
        }
    function mysql_escape_string($data){
        global $mysqli;
        return mysqli_real_escape_string($mysqli, $data);
        }
    function mysql_real_escape_string($data){
        return mysql_real_escape_string($data);
        }
    function mysql_close(){
        global $mysqli;
        return mysqli_close($mysqli);
        }
    function mysql_free_result($res){
        mysqli_free_result($res);
    }
}
function backupMySqlData($DbHost, $DbUser, $DbPwd, $DbName, $saveFileName = '')

  {

    header("Content-type:text/html;charset=utf-8");

    error_reporting(0);

    set_time_limit(0);
//    echo '数据备份中，请稍候......<br />';
    $link = mysql_connect($DbHost, $DbUser, $DbPwd) or die('数据库连接失败: ' . mysql_error());

    mysql_select_db($DbName) or die('数据库连接失败: ' . mysql_error());

    mysql_query('set names utf8');



    // 声明变量

    $isDropInfo   = '';

    $insertSQL   = '';

    $row      = array();

    $tables     = array();

    $tableStructure = array();

    $fileName = $saveFileName ? $saveFileName : 'MySQL_data_bakeup_';



    // 枚举该数据库所有的表

    $res = mysql_query("SHOW TABLES FROM $DbName");

    while ($row = mysql_fetch_row($res)) {



      $tables[] = $row[0];



    }

    mysql_free_result($res);

    // 枚举所有表的创建语句
    foreach ($tables as $val) {
//       echo $val;
//      if($val != 'note' && $val != 'user'){
            $res = mysql_query("show create table $val", $link);

            $row = mysql_fetch_row($res);



            $isDropInfo   = "DROP TABLE IF EXISTS `" . $val . "`;\r\n";

            $tableStructure = $isDropInfo . $row[1] . ";\r\n";

            file_put_contents($fileName, $tableStructure, FILE_APPEND);

            mysql_free_result($res);
//      }

    }



    // 枚举所有表的INSERT语句

    foreach ($tables as $val) {
//        echo $val;
//        if($val != 'note' && $val != 'user'){

          $res = mysql_query("select * from $val");



          // 没有数据的表不执行insert

          while ($row = mysql_fetch_row($res)) {



            $sqlStr = "INSERT INTO `".$val."` VALUES (";



            foreach($row as $v){


              if($v == ''){
                $v = 'null';
                $sqlStr .= $v.',';
              }else{
                $sqlStr .= "'$v',";
              }

    //          echo $v.'<br>';



            }

            //去掉最后一个逗号

            $sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 1);

            $sqlStr .= ");\r\n";

//            echo $sqlStr;


            file_put_contents($fileName, $sqlStr, FILE_APPEND);

            }

            mysql_free_result($res);

//          }

        }



    echo '0';

  }
  backupMySqlData($config['host'], $config['user'], $config['password'], $config['dbName'], $filePathName)

?>