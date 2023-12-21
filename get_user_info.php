<?php
// 이 부분에 사용자 정보를 가져오는 코드를 추가
// 예를 들어, 세션을 이용한 사용자 정보 가져오기 등

session_name('로그인세션'); //세션 명시 해줘야 함. 어떤 세션을 시작할 것인지
//session_name 함수는 현재 세션의 이름을 가져오거나 설정하는 데 사용됩니다.
session_start(); //세션시작

// 여기에서는 임시로 userId와 userName을 설정
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
$userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : null;

// 사용자 정보를 연관 배열로 생성
$userInfo = array(
    'userId' => $userId,
    'userName' => $userName
);

// JSON 형태로 변환하여 출력
echo json_encode($userInfo);
?>
