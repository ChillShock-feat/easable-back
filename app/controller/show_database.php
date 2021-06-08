<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

$DB_function = new DBFunction();
$pdo = $DB_function->DB_connect();

$ip_address = $DB_function->DB_index_ip_adddress($pdo, $user_id); //ユーザIDはクッキーから取ってくる
$json = $DB_function->;
echo json_encode($json);
