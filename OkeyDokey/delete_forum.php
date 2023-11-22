<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    //

    //1. mysql과 연결하기
    $host = '52.79.41.79';
    $user = 'datagrip';
    $pw = 'abc123';
    $db_name = 'mydb';

    $connect = mysqli_connect($host, $user, $pw, $db_name);

    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    //2. post요청으로 도착한 $forumId를 받기
    //post 요청을 보낼때 key인 name(deleteForum)로 값이 있는지 확인한 후에 있으면 쿼리문을 실행한다
    $deleteForumId = isset($_POST['deleteForum']) ? $_POST['deleteForum'] : null;

    if ($deleteForumId === null) {
        echo '<script>alert("글 ID를 찾을 수 없습니다.");</script>';
        echo '<script>history.back();</script>';
        exit; // Exit to avoid further execution
    }

    //3. 삭제 쿼리문 실행
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

?>