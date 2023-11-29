<?php

    //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //1. mysql과 연결하기
    include 'config_pdo.php'; // PDO 설정 파일

    
    //2. post요청으로 도착한 $deleteAnswerId를 받기
    //post 요청을 보낼때 key인 name(deleteAnswer)로 값이 있는지 확인한 후에 있으면 쿼리문을 실행한다
    $deleteAnswerId = isset($_POST['deleteAnswer']) ? $_POST['deleteAnswer'] : null;

   try{
    // PDO 객체를 생성
    // 데이터베이스에 연결하는 데 사용됩니다.
    //PDO 연결 문자열에서 공백이 있는 부분을 수정 => 문자열 내에 공백이 있으면 오류를 일으킬 수 있습니다.
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=$dbChar", $user, $pw);

    // 에러 모드 설정
    // PDO::ERRMODE_EXCEPTION로 설정하여 예외(exception)가 발생하면 예외를 던지도록 합니다.
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if ($deleteAnswerId === null) {
        echo '<script>alert("댓글 ID를 찾을 수 없습니다.");</script>';
        echo '<script>history.back();</script>';
        exit; // Exit to avoid further execution
    }

    //3. 삭제 쿼리문 작성
    $sql = "DELETE FROM answers WHERE answerId = :deleteAnswerId";

    //prepared statement 생성
    $stmt = $pdo -> prepare($sql);


    //매개변수 바인딩 및 실행
    $stmt->bindParam(':deleteAnswerId', $deleteAnswerId,  PDO::PARAM_INT);
    $stmt->execute();

    // 삭제 성공 여부에 따른 처리
    if($stmt -> rowCount() > 0){
        echo '<script>alert("글이 삭제되었습니다.");</script>';
        echo '<script>history.back();</script>';
    }else{
        echo '<script>alert("글삭제에 실패하였습니다");</script>';
        echo '<script>history.back();</script>';

    }

    }catch (PDOException $e) {
        // PDO 예외 발생 시 처리
        echo '<script>alert("데이터베이스 오류: ' . $e->getMessage() . '");</script>';
        echo '<script>history.back();</script>';
    }
    
?>