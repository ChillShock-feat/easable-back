<?php
require_once ("user.php");
$path = "./example/user1/";
$userHandle = new User();

// ユーザ名取得
$user_name = $_SESSION['user_name'];
// ユーザ名をファイル名と連結してパス完成
$path = $path . $user_name;

//連想配列のKEY値とCRUD(insert,update,etc)取得
list($keies,$crudHandle) = $userHandle->getKeyCrud($_POST);

$data['sql'] = $userHandle->createSql($keies,$crudHandle);

$function_file = <<<EOM
<?php
// 文字コード設定
    header('Content-Type: text/html; charset=UTF-8');
    
\$dsn = "mysql:host={$db_server};dbname={$db_name};port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";
        \$pdo = new PDO(\$dsn, \$db_user, \$db_password);
        \$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        \$input_parameters=NULL;
        $sql;
        \$stm = \$pdo->prepare(\$sql);
        \$result = \$stm->execute(\$input_parameters);
        return \$result;

    \$url = 'https://back.easable.jp/easable-back/app/user/api.php';
    \$data = [];

    // \$dataに送るデータを詰めます。
    \$data['select'] = "SELECT id FROM user";
    // \$data['num'] = 10;

    // 送信データをURLエンコード。
    \$data = http_build_query(\$data, "", "&");

    // これを指定しないと動かない？
    \$header = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".strlen(\$data)
    ];
    // 送信の準備(ストリームを作る)
    \$options =[
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", \$header),
        'content' => \$data
    ]
    ];

    \$context = stream_context_create(\$options);

    \$data = file_get_contents(\$url, false, \$context);

    \$data = json_decode(\$data);

    if(\$data->status == "200") {
        print json_encode(\$data, JSON_PRETTY_PRINT);    
    }
        
EOM;

$fileName = $userHandle->createFileName($crudHandle);
//ファイルの出力
file_put_contents("{$fileName}.php",$function_file);

//ユーザの処理Path作成(テスト)
$uri = $_SERVER['REQUEST_URI'];
$user_handle_path = rtrim($uri,substr($uri, strrpos($uri, '/') + 1)).substr($fileName, strrpos($fileName, '/') + 1).".php";

?>