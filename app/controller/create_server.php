<?php
//作成中です!! by asahi
session_start();
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

$project_id = $_POST['project_id'];
$user_id = $_SESSION['user']['id'];
$server_name = $_POST['server'];

//新規追加のサーバー個数
foreach($server_name as $server){
    //データベースに新しいseverを追加

    //mysqlのテーブルのserverテーブルのカラムにproject_idを追加して欲しい
    $DB_function->DB_createServer($pdo,$server,$project_id,$user_id);

    //Userサーバーでコンテナを立てる

}

//次のページにリダイレクトさせる
header('https://www.easable.jp/easable-app/done.php');