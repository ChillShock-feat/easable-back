<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../validate/regist_user_validate.php');
require_once(dirname(__FILE__) . '/../../config/email.php');

session_start();

//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャギング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//エラーメッセージの初期化
$errors = array();

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    //メールアドレス空欄
    if (empty($_POST['email'])) {
        $errors['email'] = 'メールアドレスが未入力です。';
    } else {
        //POSTされたデータを変数に入れる
        $email = isset($_POST['email']) ? $_POST['email'] : NULL;

        //メールアドレス構文チェック
        // code

        //すでにメールアドレスが登録されているか確認
        $result = $DB_function->DB_check_user($pdo, $email);
        if (isset($result["id"])) {
            $errors['check_user'] = "このメールアドレスはすでに利用されております。";
        }

        //エラーがない場合、pre_userテーブルにインサート
        if (count($errors) === 0) {
            $urltoken = hash('sha256', uniqid(rand(), 1));
            $url = "http://192.168.56.101/app/controller/signup.php?urltoken=" . $urltoken;

            //登録できたらメッセージを返す
            $message = $DB_function->DB_regist_user($pdo, $urltoken, $email);
            //メール送信処理
            //mb_send_mail($email, SIGNUP_MAIL_TITLE, SIGNUP_MAIL_SUBJECT, HEADERS);
        }
    }
    header("Location:http://localhost/easable-app/registration_sample/done.php?url={$url}");
}
