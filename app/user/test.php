<?php
// 文字コード設定
header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

// numパラメータにセットする値
$num = $_GET['num'];
// WebAPIのURL
$url = "https://www.easable.jp/easable-app/api.php?num=${num}";

// $url = "https://www.easable.jp/easable-app/api.php?num=${num}";

// URLの内容を取得し、json形式からstdClass形式に変換し取得
$text = file_get_contents($url);

$data = json_decode(file_get_contents($url));

if(isset($data)){
    echo json_encode($data);
}else{
    echo json_encode(["error"]);
}