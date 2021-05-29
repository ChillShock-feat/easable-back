<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    $DB_function->DB_regist_user($pdo, $_POST['password'], $_POST['name']);

    //セッション変数を全て解除
    $_SESSION = array();
    //セッションクッキーの削除
    if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    //セッションを破棄する
    session_destroy();

    header("Location: " . WEB_SERVER . "/registration_sample/regist_OK.html");
}
