<?php
//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);
//

//1. mysql과 연결하기
include 'config.php';

//2. 입력값이 비었는지 확인
$title = $_POST['title'];
$description = $_POST['description'];

//3. INSERT 쿼리 전송 (회원정보는 고정된 값을 넣어줌)
$query = "INSERT INTO forums (forumTitle, forumContents, userId) 
          VALUES ('$title', '$description', 3)";

$result = $connect->query($query);
if($result){
    echo '<script>alert("글이 등록되었습니다.");</script>';
    echo "<script>location.replace('forumList.html');</script>";

}else{
    echo "<script>alert('글 등록에 실패하였습니다.');</script>";
    echo '<script>history.back();</script>';
}          

// 데이터베이스 연결 닫기
$connect->close();

?>