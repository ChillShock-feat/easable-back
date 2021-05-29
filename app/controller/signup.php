<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');

session_start();

//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//成功・エラーメッセージの初期化
$errors = array();

$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (empty($_GET)) {
    header("Location: registration_mail");
    exit();
} else {
    //GETデータを変数に入れる
    $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;

    //メール入力判定
    if ($urltoken == '') {
        $errors['urltoken'] = "トークンがありません";
    } else {
        try {
            $sql = "SELECT email FROM pre_user WHERE urltoken=(:urltoken) AND flag = 0 AND date > now() - interval 24 hour";
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
}
