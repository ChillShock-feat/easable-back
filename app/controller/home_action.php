<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['create_server'])) {
    var_dump("サーバー作成");
} else if (isset($_POST['create_function'])) {
    header("Location:" . WEB_SERVER . "/easable-app/user_db_controller.php");
}
