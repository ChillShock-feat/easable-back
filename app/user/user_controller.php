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

$sql = $userHandle->createSql($keies,$crudHandle);

$function_file = <<<EOM
<?php
\$dsn = "mysql:host={$db_server};dbname={$db_name};port=3306;charser=utf8;unix_socket=/tmp/mysql.sock'";
        \$pdo = new PDO(\$dsn, \$db_user, \$db_password);
        \$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        \$input_parameters=NULL;
        $sql;
        \$stm = \$pdo->prepare(\$sql);
        \$result = \$stm->execute(\$input_parameters);
        return \$result;
        
EOM;

$fileName = $userHandle->createFileName($crudHandle);
//ファイルの出力
file_put_contents("{$fileName}.php",$function_file);

//ユーザの処理Path作成(テスト)
$uri = $_SERVER['REQUEST_URI'];
$user_handle_path = rtrim($uri,substr($uri, strrpos($uri, '/') + 1)).substr($fileName, strrpos($fileName, '/') + 1).".php";

?>