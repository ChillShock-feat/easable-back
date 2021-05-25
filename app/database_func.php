<?php
require_once(dirname(__FILE__) . '/../config/database.php');

class DBFunction
{
    public function DB_connect()
    {
        $dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";charser=utf8;unix_socket=/tmp/mysql.sock'";
        $pdo = new PDO($dsn, DBUSER, DBPASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
