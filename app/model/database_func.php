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

    public function DB_regist_pre_user($pdo, $urltoken, $email)
    {
        try {
            $sql = "INSERT INTO pre_user (urltoken, email, date, flag) 
                    VALUES (:urltoken, :email, now(), '0')";
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

    public function DB_access_token($pdo, $urltoken)
    {
        try {
            $sql = "SELECT email FROM pre_user 
                    WHERE urltoken=(:urltoken) 
                    AND flag = 0 
                    AND date > now() - interval 24 hour";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
            $stm->execute();

            //レコード件数取得
            $row_count = $stm->rowCount();

            //24時間以内に仮登録され、本登録されていないトークンの場合
            if ($row_count == 1) {
                $email_array = $stm->fetch();
                $email = $email_array["email"];
                $_SESSION['email'] = $email;
                var_dump($_SESSION['email']);
            } else {
                $errors['urltoken_timeover'] = "このURLは利用できません。有効期限が過ぎた、もしくはURLが間違えている可能性がございます。恐れ入りますが一度登録し直すようお願いいたします。";
            }
            //データベース接続切断
            $stm = null;
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    public function DB_regist_user($pdo, $password, $name)
    {
        //パスワードのハッシュ化
        $password_hash =  password_hash($password, PASSWORD_DEFAULT);

        var_dump($_SESSION['email']);

        //ここでデータベースに登録する
        try {
            $sql = "INSERT INTO user (password,name,email,login_status,user_status,created_at,updated_at) 
                    VALUES (:password_hash,:name,:email,1,0,now(),now())";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stm->bindValue(':name', $name, PDO::PARAM_STR);
            $stm->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
            $stm->execute();

            //pre_userのflagを1にする(トークンの無効化)
            $sql = "UPDATE pre_user SET flag=1 WEHERE email=:email";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
            $stm->execute();

            $stm = null;
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }
}
