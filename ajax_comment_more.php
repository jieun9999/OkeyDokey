<?php
    include('config_mysqli.php');
    session_name('로그인세션'); //세션 명시 해줘야 함. 어떤 세션을 시작할 것인지
    //session_name 함수는 현재 세션의 이름을 가져오거나 설정하는 데 사용됩니다.
    session_start(); //세션시작

    $lastmsg = $_POST['lastmsg'];
    $answerId = $_POST['answerId'];

    $result=mysqli_query($connect, "SELECT * , users.userName AS commentUserName, users.userImg AS commentUserImg, users.userId AS commentUserId
    from comments
    LEFT JOIN users ON comments.userId = users.userId
    WHERE commentId < $lastmsg AND comments.answerId = $answerId
    ORDER BY commentId DESC LIMIT 3");
    //comments 테이블에서 commentId 값이 $lastmsg보다 작은 것을 가져와 내림차순으로 정렬한 후에 상위 3개의 결과만을 가져오는 것이다.

    $lastCommentId = null; // 마지막 답변의 ID를 추적하기 위한 변수 초기화

    //forumSpecific.html로 내보내는 html 데이터들
    while ($comment= mysqli_fetch_assoc($result))
        {
            $commentId = $comment['commentId'];    
            /* 더보기 버튼에 가장 마지막 commentId 할당 */
            $lastCommentId = $commentId; // 현재 댓글의 ID를 저장

    ?>
                <div class="card card-inner">
                  <div class="media">
                    <div class="media-body comment-contents">
                      <?php echo $comment['commentContents']?>
                    </div>
                  </div>
                  <div class="comment-profile">
                    <div class="comment-date"><?php echo $comment['commentDate']?></div>
                    <!-- 나중에 수정하면 update된 date가 렌더링 되도록 해야함-->
                    <div class="author"><?php echo $comment['commentUserName']?></div>
                  </div>

                  <!-- 코멘트용 수정, 삭제 버튼-->
                  <div class="buttons-forums btn-comment-container">

                    <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == $comment['commentUserId']): ?>
            
                  <form action="edit_comment.html" method="post"> 
                  <input type="hidden" name="editCommentContents" class="btn btn-dark btn-comment" value="<?php echo $comment['commentContents'] ?>"/>
                  <input type="hidden" name="editCommentForumId" value="<?php echo $comment['forumId'] ?>" />
                    <button type="submit" name="editCommentId" class="btn btn-dark btn-comment" value="<?php echo $comment['commentId'] ?>">수정</button>
                  </form>

                  <form id="comment_deleteForm" action="delete_comment.php" method="post">
                  <button type="submit" name="deleteCommentId" class="btn btn-danger btn-comment btn-delete" value="<?php echo $comment['commentId'] ?>" onclick="confirmDeleteComment()">삭제</button>
                  <!-- type를 submit으로 해줘야 form이 자동으로 제출됨! -->
                  </form>
            
                  <!-- if(isset($_SESSION['userId']) && $_SESSION['userId'] == $comment['commentUserId']) 조건문 종료-->
                  <?php endif; ?>
                  </div> 
                </div>

        <?php
        }
?>

          <!-- 댓글 더보기 버튼 -->
            <!-- 가장 마지막에 출력된 $lastCommentId를 id값으로 가지고 있다. -->
            <!-- 어떤 답변에 대한 댓글인지 알려주기 위해 $answerId를 id값으로 가지고 있다. -->
            <div id="more<?php echo $lastCommentId; ?>" class="morebox">
                <button id="<?php echo $lastCommentId; ?>" type="button" class="btn btn-outline-primary more">더보기</button>
                <input type="hidden" id="<?php echo  $answerId; ?>"/>
            </div> 

