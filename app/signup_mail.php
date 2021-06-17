<?php
require_once(dirname(__FILE__) . '/../config/database.php');
require_once(dirname(__FILE__) . '/database_func.php');

//定数展開用
$_ = function ($s) {
    return $s;
};

session_start();

//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャギング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//エラーメッセージの初期化
$errors = array();

//DB接続
$connect = new DBFunction;
$pdo = $connect->DB_connect();

if (isset($_POST['submit'])) {
    //メールアドレス空欄
    if (empty($_POST['email'])) {
        $errors['email'] = 'メールアドレスが未入力です。';
    } else {
        //POSTされたデータを変数に入れる
        $mail = isset($_POST['email']) ? $_POST['email'] : NULL;

        //メールアドレス構文チェック
        // if (!preg_match("¥/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)) {
        //     $errors['mail_check'] = "メールアドレスの形式が正しくありません。";
        // }

        //DB確認        
        $sql = "SELECT id FROM user WHERE email=:email";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':email', $mail, PDO::PARAM_STR);

        $stm->execute();
        $result = $stm->fetch(PDO::FETCH_ASSOC);
        //user テーブルに同じメールアドレスがある場合、エラー表示
        if (isset($result["id"])) {
            $errors['user_check'] = "このメールアドレスはすでに利用されております。";
        }

        //エラーがない場合、pre_userテーブルにインサート
        if (count($errors) === 0) {
            $urltoken = hash('sha256', uniqid(rand(), 1));
            $url = "https://localhost:8080/signup.php?urltoken=" . $urltoken;
            //ここでデータベースに登録する
            try {
                //例外処理を投げる（スロー）ようにする
                $sql = "INSERT INTO pre_user (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
                $stm->bindValue(':email', $mail, PDO::PARAM_STR);
                $stm->execute();
                $pdo = null;
                $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
            } catch (PDOException $e) {
                print('Error:' . $e->getMessage());
                die();
            }
        }
    }
}

?>

<h1>仮会員登録画面</h1>
<?php if (isset($_POST['submit']) && count($errors) === 0) : ?>
    <!-- 登録完了画面 -->
    <p><?= $message ?></p>
    <p>↓TEST用(後ほど削除)：このURLが記載されたメールが届きます。</p>
    <a href="<?= $url ?>"><?= $url ?></a>
<?php else : ?>
    <!-- 登録画面 -->
    <?php if (count($errors) > 0) : ?>
        <?php
        foreach ($errors as $value) {
            echo "<p class='error'>" . $value . "</p>";
        }
        ?>
    <?php endif; ?>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
        <p>メールアドレス：<input type="text" name="email" size="50" value="<?php if (!empty($_POST['email'])) {
                                                                        echo $_POST['email'];
                                                                    } ?>"></p>
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="submit" name="submit" value="送信">
    </form>
<?php endif; ?>
