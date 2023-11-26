<?php
  //이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  //1. mysql과 연결하기
  include 'config_mysqli.php';

  if (isset($_POST['forumId']) && isset($_POST['voteType'])) {

  $forumId = $_POST['forumId'];
  $voteType = $_POST['voteType'];


  // 2. UPDATE 쿼리 작성
  if($voteType === "up"){
    $sql = "UPDATE forums SET  forumVotingCounts =  forumVotingCounts +1 WHERE forumId = $forumId";
  }elseif($voteType === "down"){
    $sql = "UPDATE forums SET  forumVotingCounts =  forumVotingCounts - 1 WHERE forumId = $forumId";
  }else{
    // $voteType이 "up" 또는 "down"이 아닌 경우에 대한 처리
    echo "Invalid voteType!";
    exit();
  }

  // 3. sql 쿼리 실행
  $result = $connect ->query($sql);

  // (update_votes_ajax.php)에서 값을 반환하지 않으면 JavaScript 성공 함수에 사용할 데이터가 없습니다.
  // (중요) 즉 success의 res 인자가 되줄 값을 echo하거나 return해야 합니다!!!

  if($result){
     // Query was successful, fetch and return the updated vote count
    $updatedVoteCount = fetchUpdateVoteCount($forumId);
    echo $updatedVoteCount;
  }else {
    // Query failed
    echo "Failed to update vote count.";
}

}else{
    // Handle the case where keys are not set
    echo "Missing keys in the POST request.";
}

// 특정 forumId에 대해 업데이트된 투표 수를 가져오는 함수
function fetchUpdateVoteCount($forumId){

    global $connect;
    $sql2 = "SELECT forumVotingCounts FROM forums WHERE forumId = $forumId";
    $result2 = $connect -> query($sql2);

    if($result2 && $row = $result2->fetch_assoc()){
        return $row['forumVotingCounts'];
    }else {
        return "Error fetching updated vote count.";
    }
}

?>