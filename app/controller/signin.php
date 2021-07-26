<?php
session_start();
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    if ($DB_function->userLogin($pdo, $_POST['email'], $_POST['password'])) {
        header("Location:" . WEB_SERVER . "/easable-app/home.php");
    } else {
        header("Location:" . WEB_SERVER . "/easable-app/signin.php");
    }
}