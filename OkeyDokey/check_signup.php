<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
session_start();

$host = '3.39.243.166';
$user = 'datagrip';
$pw = 'abc123';
$db_name = 'mydb';
$conn = mysqli_connect($host, $user, $pw, $db_name);

$username = $_POST['username'];
$signup_email = $_POST['email'];
$signup_pw = $_POST['password'];
$signup_pw_confirm = $_POST['confirm_password'];

//1. 비어 있는 항목이 있는지 검사
if($username == "" || $signup_email == "" || $signup_pw == "" || $signup_pw_confirm == ""){
    echo '<script>alert("비어있는 항목이 있습니다.");</script>';
    echo '<script>history.back();</script>';
}

//2. 비밀번호와 비밀번호 확인이 일치하는지 검사
if($signup_pw != $signup_pw_confirm){
    echo '<script>alert("비밀번호가 일치하지 않습니다.");</script>';
    echo '<script>history.back();</script>';
}

//3. MySQL에 데이터를 넣고 알림창에 메시지 출력
$sql = "INSERT INTO signup (userName, userEmail, userPw) VALUES ('$username', '$signup_email', '$signup_pw')";

   mysqli_query($conn, $sql);
   //주어진 연결 객체 ($conn)를 사용하여 주어진 SQL 쿼리 ($sql)를 실행하는 것입니다.
   echo '<script>alert("회원 가입이 완료되었습니다.");</script>';
   echo "<script>location.replace('signin.html');</script>";

?>