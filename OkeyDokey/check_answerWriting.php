<?php

//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_name('로그인세션'); //세션 명시 해줘야 함. 어떤 세션을 시작할 것인지
//session_name 함수는 현재 세션의 이름을 가져오거나 설정하는 데 사용됩니다.
session_start(); //세션시작

// 1.PDO를 계속 사용하려면 MySQLi 관련 코드를 제거함
include 'config_pdo.php';

//2. 입력값 확인
$answerContents = $_POST['replyText'];
$forumId = $_POST['answersForumId'];


// 3.mysql pdo로 mysql injection을 방어
// 원리: $username이라는 사용자 입력값을 받아서 안전하게 처리한다음, 매개변수인 :username에 넣는다는 소리 
//      (매개변수 바인딩)

try{
    // PDO 객체를 생성
    // 데이터베이스에 연결하는 데 사용됩니다.
    //PDO 연결 문자열에서 공백이 있는 부분을 수정 => 문자열 내에 공백이 있으면 오류를 일으킬 수 있습니다.
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=$dbChar", $user, $pw);

    // 에러 모드 설정
    // PDO::ERRMODE_EXCEPTION로 설정하여 예외(exception)가 발생하면 예외를 던지도록 합니다.
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //4. MySQL에 데이터를 넣고 알림창에 메시지 출력

    //쿼리 작성
    // ':answerContents', ':userId', ':forumId' 는 바인딩할 매개변수립니다
    $sql= "INSERT INTO answers (answerContents, userId, forumId) VALUES (:answerContents, :userId, :forumId)";

    // PDO Statement 객체 생성
    // PDOStatement 객체를 생성하고, 위에서 작성한 SQL 쿼리를 준비합니다.
    $stmt = $pdo -> prepare($sql);

    // 매개변수 바인딩
    // PDO::PARAM_STR은 문자열 데이터 타입임을 나타냅니다.
    // :userId 매개변수에  $_SESSION['userId']를 바인딩합니다. 
   $stmt ->bindParam(':answerContents', $answerContents, PDO::PARAM_STR);
   $stmt ->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
   $stmt ->bindParam(':forumId', $forumId, PDO::PARAM_INT);

   // 쿼리 실행
   $stmt -> execute();

   // 성공 메시지 출력
   echo '<script>alert("답변이 등록되었습니다.");</script>';
   echo "<script>location.replace('forumList.html');</script>";

}catch(PDOException $ex){
    // 실패 메시지 출력
    die($ex->getMessage());
    echo "<script>alert('답변 등록에 실패하였습니다.');</script>";
    echo '<script>history.back();</script>';

}finally{
    //PDO 연결 종료
    $pdo = null;
}

?>