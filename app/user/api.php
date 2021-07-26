<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
// 文字コード設定
header('Content-Type: application/json; charset=UTF-8');

if(isset($_GET["num"]) && !preg_match('/[^0-9]/', $_GET["num"])){
    //numをエスケープ(xss対策)
    $param = htmlspecialchars($_GET["num"]);
    // メイン処理
    $arr["status"] = "200";
    $arr["x114"] = (string)((int)$param * 114); // 114倍
    $arr["x514"] = (string)((int)$param * 514); // 514倍
} elseif(isset($_POST["num"]) && !preg_match('/[^0-9]/', $_POST["num"])) {
   //numをエスケープ(xss対策)
   $param = htmlspecialchars($_POST["num"]);
   // メイン処理
   $arr["status"] = "200";
   $arr["x114"] = (string)((int)$param * 114); // 114倍
   $arr["x514"] = (string)((int)$param * 514); // 514倍
}elseif(isset($_POST["select"])) {
    $DB_function = new DBFunction;
    $pdo = $DB_function->DB_connect();
    $arr['data'] = $DB_function->test($pdo,$_POST["select"]);
    // $userHandle = new user_database_func($_POST['db_dns'],$_POST['db_user'],$_POST['db_pass']);
    $arr["status"] = "200";
}elseif(isset($_POST["update"])){
    $arr["status"] = "200";
}elseif(isset($_POST['insert'])){
    $arr["status"] = "200";
}elseif(isset($_POST['delete'])){
    $arr["status"] = "200";
}else{
    // paramの値が不適ならstatusをnoにしてプログラム終了
    $arr["status"] = "404";
}

// 配列をjson形式にデコードして出力, 第二引数は、整形するためのオプション
echo json_encode($arr, JSON_PRETTY_PRINT);