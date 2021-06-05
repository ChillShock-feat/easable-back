<?php
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../validate/regist_user_validate.php');
// require_once(dirname(__FILE__) . '/../../config/email.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

session_start();

//CSRF対策
// $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
// $token = $_SESSION['token'];

//クリックジャギング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//エラーメッセージの初期化
$json = array();
$json['error'] = '';

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

if (isset($_POST['submit'])) {
    //メールアドレス空欄
    if (empty($_POST['email'])) {
        $json['error'] = 'メールアドレスが未入力です。'; //番号に変える
    } else {
        //POSTされたデータを変数に入れる
        $email = isset($_POST['email']) ? $_POST['email'] : NULL;

        //メールアドレス構文チェック
        // code

        //すでにメールアドレスが登録されているか確認
        $result = $DB_function->DB_check_user($pdo, $email);
        if (isset($result["id"])) {
            $json['error'] = "このメールアドレスはすでに利用されております。"; //番号に変える
        }

        //エラーがない場合、pre_userテーブルにインサート
        if ($json['error'] == '') {

            $urltoken = hash('sha256', uniqid(rand(), 1));
            $url = WEB_SERVER . "registration_sample/registration.php?urltoken=" . $urltoken;

            //登録できたらOKを返す
            $json['result'] = $DB_function->DB_regist_pre_user($pdo, $urltoken, $email);
            //メール送信処理
            //mb_send_mail($email, SIGNUP_MAIL_TITLE, SIGNUP_MAIL_SUBJECT, HEADERS);
            header("Location:" . WEB_SERVER . "registration_sample/done.php?url={$url}");
        }
    }
}
