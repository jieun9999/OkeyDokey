<?php

$host = '52.79.41.79'; // 호스트 주소
$user = 'datagrip'; // DB 아이디
$pw = 'abc123'; // DB 패스워드
$db_name = 'mydb'; // DB 이름
$dbChar = "utf8";  // 문자 인코딩

$connect = mysqli_connect($host, $user, $pw, $db_name);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

?>