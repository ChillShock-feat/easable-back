<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    //登録時のEmail取ってくる用
    $token = $_POST['urltoken'];
    $email = $DB_function->DB_index_email($pdo, $_POST['urltoken']);

    $DB_function->DB_regist_user($pdo, $_POST['password'], $_POST['name'], $email);

    //セッション変数を全て解除
    $_SESSION = array();
    //セッションクッキーの削除
    if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    //セッションを破棄する
    session_destroy();

    header("Location:" . WEB_SERVER . "/easable-app/registed_user.php");
}
