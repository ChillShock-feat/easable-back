<?php
session_start();
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    // FIX : 第二引数効率悪そうやから考える
    if ($DB_function->DB_createProject($pdo, $_SESSION['user']['email'], $_POST['project_name'])) {
        header("Location:" . WEB_SERVER . "/easable-app/success.php");
    } else {
        header("Location:" . WEB_SERVER . "/easable-app/create_project.php");
    }
}
