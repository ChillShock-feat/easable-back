<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    $DB_function->userLogin($pdo,$_POST['email'],$_POST['password']);
    
    if($DB_function->userLogin($pdo,$_POST['email'],$_POST['password'])){
        header("Location:http://" . WEB_SERVER . "/easable-app/registration_sample/home.php");
    }else{
        header("Location:http://" . WEB_SERVER . "/easable-app/registration_sample/login.php");
    }
}