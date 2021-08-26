<?php
require_once(dirname(__FILE__) . '/../../config/database.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

class DBFunction
{
    public function DB_connect()
    {
        $dsn = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME . ";port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";
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

            return 'OK';
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    public function DB_access_token($pdo, $urltoken)
    {
        try {
            $sql = "SELECT * FROM pre_user 
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
                //データベース接続切断
                $stm = null;
                return 'OK';
            } else {
                return 'error'; //<-- "このURLは利用できません。有効期限が過ぎた、もしくはURLが間違えている可能性がございます。恐れ入りますが一度登録し直すようお願いいたします。";
            }
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    public function DB_index_email($pdo, $urltoken)
    {
        try {
            $sql = "SELECT email FROM pre_user
                    WHERE urltoken=(:urltoken)";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
            $stm->execute();

            if ($stm->rowCount() == 1) {
                $email_array = $stm->fetch();

                return $email_array["email"];
            }
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    public function DB_regist_user($pdo, $password, $name, $email)
    {
        $password_hash =  password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO user (password,name,email,login_status,user_status,created_at,updated_at) 
                    VALUES (:password_hash,:name,:email,0,0,now(),now())";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stm->bindValue(':name', $name, PDO::PARAM_STR);
            $stm->bindValue(':email', $email, PDO::PARAM_STR);
            $stm->execute();

            //pre_userのflagを1にする(トークンの無効化)
            $sql = "UPDATE pre_user SET flag=1 WHERE email=:email";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':email', $email, PDO::PARAM_STR);
            $stm->execute();

            $stm = null;
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    /**
     * ログイン処理
     * @param string $email
     * @param string $password
     * @return boolean
     */

    public function userLogin($pdo, $email, $password)
    {
        $sql = "SELECT * FROM user WHERE email=:email";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':email', $email, PDO::PARAM_STR);
        $stm->execute();
        if ($stm->rowCount() == 1) {
            $user = $stm->fetch();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user']['name'] = $user['name'];
                $_SESSION['user']['email'] = $user['email'];
                $_SESSION['user']['id'] = $user['id'];

                $sql = "UPDATE user SET login_status = 1 WHERE email=:email";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':email', $email, PDO::PARAM_STR);
                $stm->execute();

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * ログアウト処理
     * @param string $email
     * @return boolean
     */

    public function userLogout($pdo, $email)
    {
        $sql = "UPDATE user SET login_status = 0 WHERE email=:email";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':email', $email, PDO::PARAM_STR);
        unset($_SESSION['user']['name'], $_SESSION['user']['email']);
        return $stm->execute();
    }

    public function DB_createProject($pdo, $user_id, $project_name)
    {
        try {
            $sql = "INSERT INTO project (user_id,project_name) 
                        VALUES (:user_id,:project_name)";
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $stm->bindValue(':project_name', $project_name, PDO::PARAM_STR);
            // $stm->execute();
            return $stm->execute();
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
            die();
        }
    }

    public function DB_createServer($pdo, $user_id, $server_name)
    {
        // 必要な情報の取得

        // ユーザ用のサーバにdocker-composeする
    }

    public function DB_indexProjectname($pdo, $user_id)
    {
    }
}
