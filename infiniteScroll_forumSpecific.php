<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include('config_mysqli.php');

    $forumId = isset($_GET['forumId']) ? $_GET['forumId'] : null;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPageItem = 5; // 페이지당 답변의 수

    try{
    // 1. 답변 렌더링
    //페이지당 시작하는 아이템행의 수 (특정 페이지에 표시되는 데이터의 시작점을 결정)
    $startRow = ($page-1) * $perPageItem;

    //LIMIT을 사용하여 가져오는 행의 수를 제한한다
    $sql = "SELECT * , users.userName AS answerUserName, users.userImg AS answerUserImg
    FROM answers 
    LEFT JOIN users ON answers.userId = users.userId
    WHERE answers.forumId = '$forumId'
    ORDER BY answers.answerId DESC LIMIT $startRow, $perPageItem";

    $result = $connect->query($sql);

    //결과가 있는 지 확인
    if($result -> num_rows > 0){

        $answers = array(); //답변들을 담을 배열을 생성합니다.

        // 결과 집합에서 데이터를 가져옵니다
        while($row = $result -> fetch_assoc()){
            $answers[] = $row;
        }

    }else{
        $answers = array();
    }

    //2. 코멘트 렌더링
    // NumberedComments라는 임시 테이블을 사용하는 Common Table Expression (CTE)로 정의하고 있고, 그 후에 해당 테이블을 활용하여 순위가 4 이하인 댓글들을 선택
    // PARTITION BY comments.answerId 는 answerId를 기준으로 그룹화하라는 의미입니다
    $commentQuery = "SELECT *, users.userName, users.userId AS commentUserId
    FROM comments 
    LEFT JOIN users ON comments.userId = users.userId
    WHERE comments.forumId = '$forumId'
    ORDER BY comments.commentId DESC";

    $commentResult = $connect ->query($commentQuery);

    //결과가 있는지 확인
    if($commentResult -> num_rows > 0){
        $comments = array(); //댓글을 담을 배열을 생성합니다.

        //결과 집합에서 데이터를 가져옵니다
        while($row2 = $commentResult ->fetch_assoc()){
            $comments[] = $row2;
        }

    }else{
        $comments = array();
    }

    // 하나의 연관 배열에 데이터를 담는다.
    if (!empty($answers)) {
        // 답변 데이터가 존재할 때
        $data = array(
            'success' => true,
            'success_comment' => !empty($comments),
            'answers' => $answers,
            'comments' => $comments
        );
    } else {
        // 답변 데이터가 존재하지 않을 때
        $data = array(
            'success' => false
        );
    }

     // PHP에서 JSON 형식으로 데이터를 생성하여 출력하는 부분
     echo json_encode($data);

    }
    catch (Exception $e) {
        // 예외가 발생하면 에러 메시지를 전송
        $data = array(
            'success' => false,
            'message' => $e->getMessage()
        );
        echo json_encode($data);
    }
    
    

?>

