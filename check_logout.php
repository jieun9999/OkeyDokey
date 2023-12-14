<?php
//특정 세션 변수 삭제
unset($_SESSION['userId']);
unset($_SESSION['userName']);

//모든 세션 변수의 등록해지
session_unset();
//세션 아이디의 삭제
session_destroy();

// 이것만으로는 클라이언트의 세션 쿠키는 삭제되지 않는다.
// 따라서, 세션쿠키를 수동으로 만료시켜야 한다
setcookie('로그인세션', '', time()-3600, '/');

//time()-3600은 현재 시간에서 1시간 전


// 유저의 아이디, 닉네임 제거 되었는지 확인하기
if(!isset($_SESSION['userId']) && !isset($_SESSION['userName'])){
echo '<script>alert("로그아웃 되었습니다.");</script>';
echo "<script>location.replace('forumList.html');</script>";

}else{
    echo '<script>alert("로그아웃에 실패하였습니다.");</script>';
    echo '<script>history.back();</script>';
}

?>