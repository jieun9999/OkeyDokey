<?php
session_start();
//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);
//

include 'config.php';

$email = $_POST['email'];
$pw = $_POST['password'];

//1. 비어있는 항목이 없는지 검사
if($email == "" || $pw == ""){
    echo '<script>alert("비어있는 항목이 있습니다.");</script>';
    echo '<script>history.back();</script>';
}

//2.입력된 사용자 정보와 일치하는지 확인하는 쿼리 작성
$sql = "SELECT * FROM users WHERE userEmail = '$email' AND userPw = '$pw'";
//$sql에 저장된 쿼리문을 MySQL 데이터베이스에 전송하고, 그 결과를 $result 변수에 저장
$result = $conn ->query($sql);

if($result -> num_rows == 1 ){
    //로그인 성공
    //SQL 쿼리에 의해 반환된 레코드의 수가 1이면, 즉 데이터베이스에서 사용자명과 비밀번호가 일치하는 사용자가 딱 하나 있다면, 로그인이 성공했다고 판단하는 조건입니다
    $_SESSION['userEmail'] = $email;
    echo "<script>location.replace('forumList.html');</script>";
    exit();
}else {
    // 로그인 실패
    echo "<script>alert('잘못 입력하셨거나 존재하지 않는 유저입니다.');</script>";
    echo '<script>history.back();</script>';
}
// 데이터베이스 연결 닫기
$conn->close();
?>