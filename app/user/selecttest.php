<?php
    // 文字コード設定
    header('Content-Type: text/html; charset=UTF-8');
    //CROS対策
    header('Access-Control-Allow-Origin: *');
    
    $dsn = "mysql:host=153.127.26.73;dbname=easable;port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";

    $url = 'https://back.easable.jp/easable-back/app/user/api.php';
    $data = [];

    // ユーザごとのデータベース設定を追加  
    $data['dsn'] = $dsn;
    $data['db_user'] = 'admin';
    $data['db_pass'] = 'easablepass';

    // $dataに送るデータを詰めます。
    $data['select'] = "SELECT name FROM table1";

    // 送信データをURLエンコード。
    $data = http_build_query($data, "", "&");

    // これを指定しないと動かない
    $header = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".strlen($data)
    ];
    // 送信の準備(ストリームを作る)
    $options =[
    'http' => [
        'method' => 'POST',
        'header' => implode("
", $header),
        'content' => $data
    ]
    ];

    $context = stream_context_create($options);

    $data = file_get_contents($url, false, $context);

    echo $data;        