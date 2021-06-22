<?php
$path = "./example/user1/";

$function_file = <<<EOM
$dsn = "mysql:host=" . {$db_server} . ";dbname=" . {$db_name} . ";port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";
        $pdo = new PDO($dsn, {$db_user}, {$db_passwprd});
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
EOM;

// ユーザ名取得
$user_name = $_SESSION['user_name'];
// ユーザ名をファイル名と連結してパス完成
$path = $path . $user_name;

if ($_POST['insert']) {

    $sql = "INSERT INTO {$_POST['table_name']} ({$_POST['name']}, {$_POST['password']})
            VALUES ({$_POST['name']}, {$_POST['password']})";

    // ファイル書き込み
    // ↓
    // ファイル出力


} else if ($_POST['delete']) {

} else if ($_POST['update']) {

} else if ($_POST['select']) {

}
?>