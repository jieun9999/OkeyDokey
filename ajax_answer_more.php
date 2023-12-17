<?php
    include('config_mysqli.php');
    session_name('로그인세션'); //세션 명시 해줘야 함. 어떤 세션을 시작할 것인지
    //session_name 함수는 현재 세션의 이름을 가져오거나 설정하는 데 사용됩니다.
    session_start(); //세션시작

    $lastmsg = $_POST['lastmsg'];
    $forumId = $_POST['forumId'];

    $result=mysqli_query($connect, "SELECT * , users.userName AS answerUserName, users.userImg AS answerUserImg
    from answers 
    LEFT JOIN users ON answers.userId = users.userId
    WHERE answerId < $lastmsg AND answers.forumId = $forumId
    ORDER BY answerId DESC LIMIT 5");
    //answers 테이블에서 answerId 값이 $lastmsg보다 작은 것을 가져와 내림차순으로 정렬한 후에 상위 5개의 결과만을 가져오는 것이다.

    $lastAnswerId = null; // 마지막 답변의 ID를 추적하기 위한 변수 초기화

    //forumSpecific.html로 내보내는 html 데이터들
    while ($rows= mysqli_fetch_assoc($result))
        {
            $answerId = $rows['answerId'];    
            /* 더보기 버튼에 가장 마지막 answerID 할당 */
            $lastAnswerId = $answerId; // 현재 답변의 ID를 저장

    ?>
        <div id="answer-container" class="container answer-container">
            <!--목록으로, 수정, 삭제 버튼-->
            <div class="buttons-forums-container">
            <div class="buttons-forums btn-answer-container">
    
            <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == $rows['answerUserId']): ?>
            // 답변 위의 버튼들
    
          <button type="button" class="btn btn-light btn-answer" onclick="goToForumList()">목록으로</button>
          <!--수정: post 요청을 보낼때 name="editAnswer"가 key이고, key는 <?php echo $rows['answerId']?>가 value가 된다.-->
          <form action="edit_answer.html" method="post">
          <button type="submit" name="editAnswer"class="btn btn-dark btn-answer" value="<?php echo $rows['answerID'] ?>">수정</button>
          <input type="hidden"  name="editAnswerForumId" value="<?php echo $rows['forumId'] ?>"/>
          </form>
          <!-- 삭제: post 요청을 보낼때 name="deleteAnswer"가 key고 <?php echo $rows['answerId']?>가 value가 된다. -->
          <form id="answer_deleteForm" action="delete_answer.php" method="post">
          <button onclick="confirmDeleteAnswer()" type="submit" name="deleteAnswer" class="btn btn-danger btn-answer" value="<?php echo $rows['answerID'] ?>">삭제</button>
          </form>
    
          <!-- if(isset($_SESSION['userId']) && $_SESSION['userId'] == $rows['answerUserId']) 조건문 종료-->
          <?php endif; ?>
             </div> 
            </div>
    
            <div class="card">
              <div class="card-body card-answers">
                  <div class="row answer-total-box">
                      <div class=" answer-profile">
                        <!-- up/down 버튼에 각각 class, data-forum-id, data-vote-type 속성을 지정해줌-->
    
                          <!-- jQuery의 data() 메서드를 통해 이 데이터를 가져오는 기법 -->
                          <!-- HTML data attribute names: kebab-case (data-forum-id, data-vote-type) -->
                        <div class = "box vote-box">
                          <div class = "vote-btn-answer up" data-answer-id="<?php echo $rows['answerId'] ?>" data-vote-type="up">&#9650;</div>
                          <div class = "voteNum"><?php echo $rows['answerVotingCounts']?></div>
                          <div class = "vote-btn-answer down" data-answer-id="<?php echo $rows['answerId'] ?>" data-vote-type="down">&#9660;</div>
                        </div>
    
                        <div class="answer-date-box">
                          <img class="answer-user-img" src="<?php echo $rows['answerUserImg']?>" class="img img-rounded img-fluid"/>
                          <div class="text-secondary text-center answerDate"><?php echo $rows['answerDate']?></div></div>
                        </div>
                      </div>
                      <div class="answer-content-box">
                          <p>
                              <a class="float-left" href="https://maniruzzaman-akash.blogspot.com/p/contact.html"><strong><?php echo $rows['answerUserName']?></strong></a>
                              <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                                <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                              <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                              <span class="float-right"><i class="text-warning fa fa-star"></i></span>
                        </p>
    
                        <div class="clearfix"></div>
                          <div class="answerContents"><?php echo $rows['answerContents']?></div>
                          <!-- 주의 :  여러 댓글 입력 컨테이너에 동일한 ID(commentFormframe)를 사용하면 버그가 생긴다-->
                          <!-- toggleCommentForm() 함수가 두번째 렌더링된 card에서 실행될때, 첫번째 card의 comment input 부분이 생겼다 사라졌다함-->
                          <!-- data-answer-id 사용해서 각각의 card를 구분할 수 있음-->
                          <a class="float-right btn btn-outline-primary ml-2" onclick="toggleCommentForm(this)" data-answer-id="<?php echo $rows['answerId'] ?>"> <i class="fa fa-reply"></i> Reply</a>
                      </div>
                  </div>
                  <!-- comments 부분 -->
    
                  <!-- comment 입력창-->
                  <div class="comment-border">
                  <!-- Use Unique IDs for Each Comment Container:-->
    
                  <!-- 전통적인 form 전송이 아니라, jquery ajax를 사용할 것임, 클라이언트가 서버에서 처리된 결과를 받아오기 위해서-->
                  <!-- commentForm이 여러개 라는 문제 발생 -->
                  <form id="commentForm_<?php echo $rows['answerId']?>" data-answer-id="<?php echo $rows['answerId'] ?>" >
                  <div class="comment-input-container" id="commentFormframe_<?php echo $rows['answerId']?>">
                  <input name="commentsForumId" value="<?php echo $forumId ?>" type="hidden"/>
                  <input name="commentsAnswerId" value="<?php echo $rows['answerId']?>" type="hidden"/>
                  <div class="text_box_comment">
                    <textarea id="commentText" name="commentText" class="comment-input" placeholder="코멘트를 작성해보세요."></textarea>
                    <div class="count"><span>0</span>/5000</div>
                  </div>
                 
                  <button type="button" class="btn btn-dark btn-dark-submit" id="submitButton_comment_<?php echo $rows['answerID']?>">submit</button>
                  </div>
                  </form>
                  <!-- 3-3. 쿼리문으로 코멘트 데이터 가져오기 -->
                  <!-- 주의 : 문자열 안에서 배열 값을 사용하려면 중괄호 {}로 감싸야 합니다-->
                  <?php
                  $commentQuery = "SELECT *, users.userName, users.userId AS commentUserId FROM comments 
                  LEFT JOIN users ON comments.userId = users.userId
                  WHERE answerId = {$rows['answerId']} AND answerId < $lastmsg 
                  order by answerId desc limit 5";
    
                  $commentResult = $connect ->query($commentQuery);
                  ?>
        
                  <!-- comment 데이터들을 렌더링하기 -->
                  <div class="comments-list-container">
                  <?php while($comment = mysqli_fetch_assoc($commentResult)): ?>
                    <div class="card card-inner">
                      <div class="media">
                        <div class="media-body comment-contents">
                          <?php echo $comment['commentContents']?>
                        </div>
                      </div>
                      <div class="comment-profile">
                        <div class="comment-date"><?php echo $comment['commentDate']?></div>
                        <!-- 나중에 수정하면 update된 date가 렌더링 되도록 해야함-->
                        <div class="author"><?php echo $comment['userName']?></div>
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
                
                      <!-- if(isset($_SESSION['userId']) && $_SESSION['userId'] == $rows['answerUserId']) 조건문 종료-->
                      <?php endif; ?>
                      </div> 
                    </div>
                  <?php endwhile; ?>
                  </div>
                </div>
            </div>
        </div>

        <?php
        }
?>

    <div id="more<?php echo $lastAnswerId; ?>" class="morebox">
      <button id="<?php echo $lastAnswerId; ?>" type="button" class="btn btn-outline-primary more">더보기</button>
      <input type="hidden" id="<?php echo $forumId; ?>"/>
    </div> 
