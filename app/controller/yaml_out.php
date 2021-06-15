<?php
$add = array(
    "web" => array(
        "build" => ".",
        "port" => "5000:5000"
    ),
    "volumes" => ".:/code"
);


var_dump(yaml_emit($add));