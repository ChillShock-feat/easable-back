<?php
session_start();
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();
