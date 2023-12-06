<?php

//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_name('로그인세션'); //세션 명시 해줘야 함. 어떤 세션을 시작할 것인지
//session_name 함수는 현재 세션의 이름을 가져오거나 설정하는 데 사용됩니다.
session_start(); //세션시작

// 1.PDO를 계속 사용하려면 MySQLi 관련 코드를 제거함
include 'config_pdo.php';

// 받아와야 하는 값들 3가지
// commentContents, answerId, forumId

//2. 입력값 확인
$commentContents = $_POST['commentText'];
$answerId = $_POST['commentsAnswerId'];
$forumId = $_POST['commentsForumId'];

//서버가 오류 메시지(예: "입력창이 비어있습니다.")로 응답하더라도 JavaScript 코드는 여전히 텍스트 영역을 지우고 성공 알림을 표시합니다.
//따라서 res에 대한 status가 필요함

// 비어있는 항목이 없는지 검사
if (empty($commentContents)) {
    //실패 상태와 메세지 출력

    $response = array('status' => 'fail', 'message' => '비어있는 항목이 있습니다.');
    echo json_encode($response);

    exit; // 등록되기 전에 여기서 탈출
}

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
    $sql= "INSERT INTO comments (commentContents, userId, forumId, answerId) VALUES (:commentContents, :userId, :forumId, :answerId)";

    // PDO Statement 객체 생성
    // PDOStatement 객체를 생성하고, 위에서 작성한 SQL 쿼리를 준비합니다.
    $stmt = $pdo -> prepare($sql);

    // 매개변수 바인딩
    // PDO::PARAM_STR은 문자열 데이터 타입임을 나타냅니다.
    // :userId 매개변수에  $_SESSION['userId']를 바인딩합니다. 
   $stmt ->bindParam(':commentContents', $commentContents, PDO::PARAM_STR);
   $stmt ->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_INT);
   $stmt ->bindParam(':forumId', $forumId, PDO::PARAM_INT);
   $stmt ->bindParam(':answerId', $answerId, PDO::PARAM_INT);

   // 쿼리 실행
   $stmt -> execute();

   // JSON 형식은 데이터를 구조적으로 전달할 수 있으며, 여러 정보를 키-값 쌍으로 표현할 수 있습니다. 
   $response = array('status'=> 'success');
   echo json_encode($response);

}catch(PDOException $ex){
    // 실패 
    die($ex->getMessage());


}finally{
    //PDO 연결 종료
    $pdo = null;
}

?>
