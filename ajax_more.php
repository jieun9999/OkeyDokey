<?php
    include('config_mysqli.php');
    if(isset($_POST['lastmsg']))
    {
    $lastmsg=$_POST['lastmsg'];
    $result=mysqli_query($connect, "select * from forums where forumId < $lastmsg order by forumId desc limit 9");
    //forums 테이블에서 forumId의 값이 $lastmsg보다 작은 것을 가져와 내림차순으로 정렬한 후에 상위 9개의 결과만을 가져오는 것이다.

    //loadMore.html로 내보내는 html 데이터들
    while($row=mysqli_fetch_array($result))
    {
    $forumId=$row['forumId'];
    $forumContents=$row['forumContents'];
    ?>
    <li>
    <?php echo $forumContents; ?>
    </li>
    <?php
    }
    ?>

    <!-- 가장 마지막에 출력된 forumId를 id값으로 가지고 있다. -->
    <div id="more<?php echo $forumId; ?>" class="morebox">
    <a href="#" id="<?php echo $forumId; ?>" class="more">more</a>
    </div>
    

    <?php
    }
?>
