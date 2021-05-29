<?php
$subject = "【Easable】もう少しで会員登録完了です。";
$text = <<<EOM
【仮会員登録完了】<br>
Easableをご利用いただき誠にありがとうございます。<br>
仮登録が完了致しましたので、お知らせ致します。<br>
※もし本メールに心当たりのない場合は、破棄して頂けますようお願い申し上げます。<br>
<br>
下記URLからアクセスして認証を完了してください。<br>
https://flagup.me/activation/check/{$info['activation_code']}<br>
(有効期限：{○年○月○日})<br>
<br>
本メールは送信専用です。返信は致しかねますのでご了承ください。<br>
<br><br><br>
EOM;
