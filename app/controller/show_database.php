<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

$DB_function = new DBFunction();
$pdo = $DB_function->DB_connect();

$json = $DB_function->DB_show_database($pdo);
echo json_encode($json);
