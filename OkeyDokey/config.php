<?php

$host = '52.79.41.79';
$user = 'datagrip';
$pw = 'abc123';
$db_name = 'mydb';

$connect = mysqli_connect($host, $user, $pw, $db_name);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

?>