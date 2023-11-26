<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //1. mysql과 연결하기
    include 'config_mysqli.php';

    //2. post요청으로 도착한 EditingforumId의 값을 받기
    // post 요청을 보낼때 key인 name(EditingforumId)로 값이 있는지 확인한 후에 있으면 쿼리문을 실행한다

    $finalEditForumId = isset($_POST['EditingforumId']) ? $_POST['EditingforumId'] : null;

    // 사용자 입력값 null인지 확인하기
    $finalTitle = $_POST['title'];
    $finalContents = $_POST['description'];

    // + 비어 있는 항목이 있는지 검사
    if($finalTitle == "" || $finalContents == "" ){
        echo '<script>alert("비어있는 항목이 있습니다.");</script>';
        echo '<script>history.back();</script>';
    }

    if ($finalEditForumId === null) {
        echo '<script>alert("글 ID를 찾을 수 없습니다.");</script>';
        echo '<script>history.back();</script>';
        exit; // Exit to avoid further execution
    }


    //3. 수정 쿼리문 실행
    $sql = "UPDATE forums SET forumTitle = '$finalTitle' ,
                              forumContents = '$finalContents',
                              forumUpdateDate = CURRENT_TIMESTAMP
                              WHERE forumId = $finalEditForumId";

    $result = $connect ->query($sql);

    // 쿼리 성공 여부 확인
    if($result){
        echo '<script>alert("글이 성공적으로 수정되었습니다.");</script>';
        //get 요청으로 수정한 글 상세보기로 이동
        $redirectUrl = "http://52.79.41.79/OkeyDokey/forumSpecific.html?글아이디=$finalEditForumId";
        echo "<script>location.replace('$redirectUrl');</script>";
    }else{
        echo '<script>alert("글 수정에 실패했습니다.");</script>';
        echo '<script>history.back();</script>';
    }

    // MySQL 연결 종료
    $connect->close();

    ?>