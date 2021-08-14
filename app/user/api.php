<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
// 文字コード設定
header('Content-Type: application/json; charset=UTF-8');
require_once(dirname(__FILE__) . '/user_database_func.php');
require_once(dirname(__FILE__) . '/../../config/database.php');

// $dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";port=3306;charser=utf8;";
$user_database_func = new user_database_func($dsn,DBUSER,DBPASSWORD);

// $user_database_func = new user_database_func($_POST['dsn'],$_POST['db_user'],$_POST['db_pass']);

if(isset($_POST["select"])) {
    $arr['data'] = $user_database_func->selectProcess($_POST['select']);
    $arr["status"] = "200";
}elseif(isset($_POST["update"])){
    $flag = $user_database_func->updateProcess($_POST['update']);
    if($flag){
        $arr["status"] = "200";
    }else{
        $arr["status"] = "400";
    }
}elseif(isset($_POST['insert'])){
    $flag = $user_database_func->updateProcess($_POST['insert']);
    if($flag){
        $arr["status"] = "200";
    }else{
        $arr["status"] = "400";
    }
}elseif(isset($_POST['delete'])){
    $flag = $user_database_func->updateProcess($_POST['delete']);
    if($flag){
        $arr["status"] = "200";
    }else{
        $arr["status"] = "400";
    }
}else{
    // paramの値が不適ならstatusをnoにしてプログラム終了
    $arr["status"] = "400";
}

// 配列をjson形式にデコードして出力, 第二引数は、整形するためのオプション
echo json_encode($arr, JSON_PRETTY_PRINT);