<?php
require_once('../config/database.php');
session_start();

//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャギング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//エラーメッセージの初期化
$errors = array();


//定数展開用
$_ = function ($s) {
    return $s;
};

//DB接続
$dsn = "mysql:host={$_(DBSERVER)};dbname={$_(DBNAME)};charser=utf8";
$pdo = new PDO($dsn, DBUSER, DBPASSWORD);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
