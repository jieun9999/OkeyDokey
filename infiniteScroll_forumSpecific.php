<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include('config_mysqli.php');

    $forumId = isset($_GET['forumId']) ? $_GET['forumId'] : null;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPageItem = 5; // 페이지당 답변의 수

    //페이지당 시작하는 아이템행의 수 (특정 페이지에 표시되는 데이터의 시작점을 결정)
    $startRow = ($page-1) * $perPageItem;

    //LIMIT을 사용하여 가져오는 행의 수를 제한한다
    $sql = "SELECT * , users.userName AS answerUserName, users.userImg AS answerUserImg
    FROM answers 
    LEFT JOIN users ON answers.userId = users.userId
    WHERE answers.forumId = $forumId
    ORDER BY answerID DESC LIMIT $startRow, $perPageItem";

    $result = $connect->query($sql);

    //결과가 있는 지 확인
    if($result -> num_rows > 0){

        $answers = array(); //답변들을 담을 배열을 생성합니다.

        // 결과 집합에서 데이터를 가져옵니다
        while($row = $result -> fetch_assoc()){
            $answers[] = $row;
        }

        // 서버가 클라이언트에게 전송하는 데이터의 유형을 명시적으로 지정하는 부분이다.
        // 웹 브라우저에게 전송된 데이터의 유형을 알려주고, 그에 대한 적절한 처리를 하도록 하는데 도움이 됩니다.
        header('Content-Type: application/json');

        // PHP에서 JSON 형식으로 데이터를 생성하여 출력하는 부분
        echo json_encode(array('success' => true, 'answers' => $answers));

    }else{
        echo json_encode(array('success' => false, 'message' => '조회할 수 있는 데이터가 없습니다'));
    }

    // Close the database connection
    $connect->close();
?>