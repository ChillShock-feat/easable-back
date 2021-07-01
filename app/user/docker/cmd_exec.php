<?php
//ユーザーネーム取ってくる
$userName = "";
$dockerCmd = "sudo docker run -d -p 8080:80 --name nginx2 nginx";
exec('ls', $output);
print_r( $output);