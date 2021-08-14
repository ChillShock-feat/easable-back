<?php
// 文字コード設定
    header('Content-Type: text/html; charset=UTF-8');
    
$dsn = "mysql:host=;dbname=;port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";
        $pdo = new PDO($dsn, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $input_parameters=NULL;
        "INSERT INTO  (password) VALUES ({$_POST['password']})";
        $stm = $pdo->prepare($sql);
        $result = $stm->execute($input_parameters);
        return $result;

    $url = 'https://back.easable.jp/easable-back/app/user/api.php';
    $data = [];

    // $dataに送るデータを詰めます。
    $data['insert'] = "INSERT INTO  (password) VALUES ({$_POST['password']})";
    // $data['num'] = 10;

    // 送信データをURLエンコード。
    $data = http_build_query($data, "", "&");

    // これを指定しないと動かない？
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

    $data = json_decode($data);

    if($data->status == "200") {
        print json_encode($data, JSON_PRETTY_PRINT);    
    }
        