<?php

//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config_mysqli.php';

$email = $_POST['email'];
$pw = $_POST['password'];


// 비어있는 항목이 없는지 검사
if($email == "" || $pw == ""){
    echo '<script>alert("비어있는 항목이 있습니다.");</script>';
    echo '<script>history.back();</script>';
}

// 입력된 사용자 정보와 일치하는지 확인하는 쿼리 작성

//먼저, 이메일 먼저 일치하는 지 검사
$sql = "SELECT * FROM users WHERE userEmail = '$email'";

//$sql에 저장된 쿼리문을 MySQL 데이터베이스에 전송하고, 그 결과를 $result 변수에 저장
$result = $connect ->query($sql);
//$row 변수에는 데이터베이스에서 검색한 한 행의 값을 연관 배열로 담게 됩니다
$row = mysqli_fetch_assoc($result);

// 세션이 실행되어있는지 여부를 체크하고, 세션을 시작합니다.
if(!session_id()){
    session_name('로그인세션');
    session_start(); 

    if($result -> num_rows == 1 ){
        //SQL 쿼리에 의해 반환된 레코드의 수가 1이면, 즉 데이터베이스에서 사용자가 딱 하나 있다면, 로그인이 성공했다고 판단하는 조건입니다.
        
        if(password_verify($pw, $row['userPw']))
        // 암호화된 비번과 사용자가 입력한 비번이 동일할때,
        // 세션에 key-value 등록합니다.
        $_SESSION['userId']= $row['userId'];
        $_SESSION['userName'] = $row['userName'];

        // 로그인 성공
        echo "<script>alert('로그인에 성공했습니다!');</script>";
        echo "<script>location.replace('forumList.html');</script>";
    }else {
        // 로그인 실패
        echo "<script>alert('잘못 입력하셨거나 존재하지 않는 유저입니다.');</script>";
        echo '<script>history.back();</script>';
    }

}


// 데이터베이스 연결 닫기
$connect->close();
?>