<?php
session_start();
require_once(dirname(__FILE__) . '/../model/database_func.php');
require_once(dirname(__FILE__) . '/../../config/server.php');

//DB接続
$DB_function = new DBFunction;
$pdo = $DB_function->DB_connect();

var_dump($_SESSION['user']['email']);

if (isset($_POST['submit'])) {
    // FIX : 第二引数効率悪そうやから考える
    if ($DB_function->DB_createProject($pdo, $_SESSION['user']['id'], $_POST['project_name'])) {
        // サーバを立てる
        $url = 'https://back.easable.jp/easable-back/info.php';

        // POSTデータ
        $data = array(
            "user_id" => $_SESSION['user']['id'],
            "server_name" => $_POST['server_name'],
            "project_name" => $_POST['project_name'],
        );
        $data = http_build_query($data, "", "&");

        $header = [
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: " . strlen($data)
        ];

        // 送信の準備(ストリームを作る)
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $header),
                'content' => $data
            ],
        ];

        $context = stream_context_create($options);

        // file_get_contents($url, false, $context);

        header("Location:" . WEB_SERVER . "/easable-app/success.php");
    } else {
        header("Location:" . WEB_SERVER . "/easable-app/create_project.php");
    }
}
