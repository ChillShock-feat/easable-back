<?php
//ユーザーネーム取ってくる
$userName = "";
$port = "8080";
$dockerStartCmd = "sudo docker run -d -p {$port}:80 --name nginx2 nginx";
exec('ls', $output);
print_r($output);