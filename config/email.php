<?php
// mb_language("Japanese");
// mb_internal_encoding("UTF-8");

define(
    'HEADERS',
    array(
        'From' => 'nino.brenpy@gmail.com',
    )
);

define('SIGNUP_MAIL_TITLE', '【Easable】仮会員登録');
define(
    'SIGNUP_MAIL_SUBJECT',
    <<< EOM
    【仮会員登録完了】<br>
    Easableをご利用いただき誠にありがとうございます。<br>
    仮登録が完了致しましたので、お知らせ致します。<br>
    ※もし本メールに心当たりのない場合は、破棄して頂けますようお願い申し上げます。<br>
    <br>
    下記URLからアクセスして認証を完了してください。<br>
    http://192.168.56.101/easable-back/app/controller/signup.php?urltoken={$urltoken}<br>
    (有効期限：{○年○月○日})<br>
    <br>
    本メールは送信専用です。返信は致しかねますのでご了承ください。<br>
    <br><br><br>
    EOM
);
