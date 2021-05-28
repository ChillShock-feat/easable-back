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
    <form action="../controller/signup_send_email.php" method="post">
        <p>メールアドレス：<input type="text" name="email" size="50" value="<?php if (!empty($_POST['email'])) {
                                                                        echo $_POST['email'];
                                                                    } ?>"></p>
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="submit" name="submit" value="送信">
    </form>
<?php endif; ?>