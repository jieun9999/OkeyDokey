<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    //

    //1. mysql과 연결하기
    include 'config_mysqli.php';

    //2. post요청으로 도착한 $forumId를 받기
    //post 요청을 보낼때 key인 name(deleteForum)로 값이 있는지 확인한 후에 있으면 쿼리문을 실행한다
    $deleteForumId = isset($_POST['deleteForum']) ? $_POST['deleteForum'] : null;

    if ($deleteForumId === null) {
        echo '<script>alert("글 ID를 찾을 수 없습니다.");</script>';
        echo '<script>history.back();</script>';
        exit; // Exit to avoid further execution
    }

    //3. 만약, 답변이 존재하는 게시글이라면 삭제가 거부된다
    // 답변이 존재하는 지 확인하는 쿼리
    $checkAnswerQuery = "SELECT COUNT(*) AS answerCount FROM answers WHERE forumId = $deleteForumId";
    $answerResult = $connect->query($checkAnswerQuery);
    $answerCount = $answerResult->fetch_assoc()['answerCount'];
    //fetch_assoc() 함수를 사용하여 결과 집합에서 한 행을 연관 배열, 가져온 연관 배열에서 'answerCount' 키를 사용하여 해당 게시글에 대한 답변의 개수를 얻는다


    if($answerCount > 0){
        // 답변이 있으면 삭제 거부
        echo '<script>alert("답변이 있는 게시글은 삭제할 수 없습니다.");</script>';
        echo '<script>history.back();</script>';

    }else{
       // 답변이 없으면 삭제 진행

    //4. 삭제 쿼리문 실행
    $sql = "DELETE FROM forums WHERE forumId = '$deleteForumId'";
    $result = $connect ->query($sql);

    // 성공,실패 리턴
    if($result){
        echo '<script>alert("글이 삭제되었습니다.");</script>';
        echo "<script>location.replace('forumList.html');</script>";
    }else{
        echo '<script>alert("글삭제에 실패하였습니다");</script>';
        echo '<script>history.back();</script>';

    }

    }

?>