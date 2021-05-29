<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

session_start();

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//成功・エラーメッセージの初期化
$errors = array();

$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();
if (empty($_GET)) {
    header("Location: registration_mail");
    exit();
} else {
    //GETデータを変数に入れる
    $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;

    //メール入力判定
    if ($urltoken == '') {
        $errors['urltoken'] = "トークンがありません";
    } else {
        $DB_function->DB_access_token($pdo, $urltoken);
        header("Location: " . WEB_SERVER . "/registration_sample/registration.html");
    }
}
