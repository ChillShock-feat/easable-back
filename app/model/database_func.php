<?php
require_once(dirname(__FILE__) . '/../../config/database.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

class DBFunction
{
    public function DB_connect()
    {
        $dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";charser=utf8;unix_socket=/tmp/mysql.sock'";
        $pdo = new PDO($dsn, DBUSER, DBPASSWORD);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public function DB_check_user($pdo, $email)
    {
        $sql = "SELECT id FROM user WHERE email=:email";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':email', $email, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    public function DB_regist_user($pdo, $urltoken, $email)
    {
        try {
            $sql = "INSERT INTO pre_user (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
            $stm->bindValue(':email', $email, PDO::PARAM_STR);
            $stm->execute();
            $pdo = null;

            return "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }
}
