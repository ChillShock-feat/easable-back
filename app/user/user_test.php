<?php
require_once(dirname(__FILE__) . '/../../config/database.php');
require_once(dirname(__FILE__) . '/user_database_func.php');

$dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";port=3306;charser=utf8;";

$user_database_func = new user_database_func($dsn,DBUSER,DBPASSWORD);

$sql = "SELECT * FROM user";

$users = $user_database_func->selectProcess($sql);

var_dump($users);
