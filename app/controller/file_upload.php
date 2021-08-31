<?php
if (isset($_POST['submit'])) {
    $file = $_FILES['file'];
    //FTPサーバとアカウント情報
    $server = "easable.jp"; //送り先のFTPサーバー名もしくはIP
    $user_name = "ec2-user"; //送り先のFTPユーザ
    $user_pass = "easable-chill"; //送り先のFTPパスワード
    $remote_dir = './uploads/'; //送り先のディレクトリ
    $local_dir = './../uploads';

    // FTP接続確立
    $conn_id = ftp_ssl_connect($server);

    // ユーザ名とパスワードでログイン
    $login_result = ftp_login($conn_id, $user_name, $user_name);

    // 接続確認
    if ((!$conn_id) || (!$login_result)) {
        echo "SFTP接続できまへん";
    } else {
        echo $conn_id;
        echo $login_result;
        // // FTPサーバ上でディレクトリ移動
        // ftp_chdir($conn_id, $remote_dir);
        // move_uploaded_file($file["tmp_name"], $local_dir . $file['name']);

        // //アップロード
        // $local = $local_dir . $file['name']; //アップロードするファイル
        // $remote = $file['name']; //アップロード時の名前

        // if (!ftp_put($conn_id, $remote, $local, FTP_BINARY)) {
        //     echo "アップロードできませんでした";
        // } else {
        //     //ローカル側のファイルを削除
        //     unlink($local_dir . $file['name']);
        // }

        // //接続を閉じる
        // ftp_close($ftp);
    }
}
