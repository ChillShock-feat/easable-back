<?php
    // 文字コード設定
    header('Content-Type: text/html; charset=UTF-8');

    $url = 'https://back.easable.jp/easable-back/app/user/api.php';
    $data = [];

    // $dataに送るデータを詰めます。
    $data['select'] = "SELECT id FROM user";
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
        'header' => implode("\r\n", $header),
        'content' => $data
    ]
    ];

    $context = stream_context_create($options);

    $data = file_get_contents($url,false,$context);
    // $data = getApiDataCurl($url);

    var_dump($data);

    // $data = json_decode($data);

    // if($data->status == "200") {
    //     print json_encode($data, JSON_PRETTY_PRINT);    
    // }

//     function getApiDataCurl($url){

//     $option = [
//         CURLOPT_RETURNTRANSFER => true, //文字列として返す
//         CURLOPT_TIMEOUT        => 3, // タイムアウト時間
//     ];

//     $ch = curl_init($url);
//     curl_setopt_array($ch, $option);

//     $json    = curl_exec($ch);
//     $info    = curl_getinfo($ch);
//     $errorNo = curl_errno($ch);

//     var_dump($info);

//     // OK以外はエラーなので空白配列を返す
//     if ($errorNo !== CURLE_OK) {
//         // 詳しくエラーハンドリングしたい場合はerrorNoで確認
//         // タイムアウトの場合はCURLE_OPERATION_TIMEDOUT
//         return [];
//     }

//     // 200以外のステータスコードは失敗とみなし空配列を返す
//     if ($info['http_code'] !== 200) {
//         return [];
//     }

//     // 文字列から変換
//     $jsonArray = json_decode($json, true);

//     curl_close($ch);

//     return $jsonArray;
// }