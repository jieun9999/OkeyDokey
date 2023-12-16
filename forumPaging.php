<?php
        // 검색어가 있을 경우 렌더링과 전체페이지 렌더링 구분하기

          // 1. 페이지 번호 중 처음/이전버튼 출력
          // 여기서 \"는 큰따옴표(")를 문자열 내에서 사용하기 위한 이스케이프입니다
          if($page >1 ){
            echo "<li class='page-item'><a class='page-link' href=\"forumList.html?page=1&sort=$sortOrder&search=$searchTerm\" data-abc='true'>처음</a></li>";
          }
  
          if($page >1 ){
            $pre_to5 = $page -5;
            $pre = ($pre_to5 < 1) ? 1 : $pre_to5;
            echo "<li class='page-item'><a class='page-link' href=\"forumList.html?page=$pre&sort=$sortOrder&search=$searchTerm\" data-abc='true'>⏪️</a></li>";
          }
  
          // 2. 페이지 번호 중 1/2/3 등 숫자버튼 출력, + 현재 버튼 active 
          $total_post = mysqli_num_rows($res);
          $total_page = ceil($total_post/ $per);
          // 총 페이지 개수는 ceil() 을 사용해 (총 게시글 개수 / 페이지 당 게시글 개수) 를 올림해준 값 으로 정한다.

          // 5개씩 하나의 블록을 이룬다 

          // 현재 블록 번호 계산
          $current_block = ceil($page/5);

          // 각 블록의 시작 페이지 번호 계산
          $start_page = ($current_block-1) * 5 + 1;

          // 각 블록의 끝 페이지 번호 계산
          $end_page = min($start_page + 4, $total_page);

  
          $page_num = $start_page;
          while($page_num <= $end_page){
              if($page == $page_num){
                // 클릭한 페이지 번호와 출력할 페이지 번호가 같으면 active 처리
                  echo "<li class='page-item active'><a class='page-link' href=\"forumList.html?page=$page_num&sort=$sortOrder&search=$searchTerm\" data-abc='true'>$page_num</a></li>";
              }
              else {
                // 클릭한 페이지 번호가 아닌 다른 출력할 페이지 번호
                echo "<li class='page-item'><a class='page-link' href=\"forumList.html?page=$page_num&sort=$sortOrder&search=$searchTerm\" data-abc='true'>$page_num</a></li>";
              }
                  $page_num++;
          }
  
          // 3. 페이지 번호 중 끝/다음버튼 출력
          if($page < $total_page){
            $next_to5 = $page + 5;
            $next = min($next_to5, $total_page);
            echo "<li class='page-item'><a class='page-link' href=\"forumList.html?page=$next&sort=$sortOrder&search=$searchTerm\" data-abc='true'>⏩️</a></li>";
          }
          if($page < $total_page){
            echo "<li class='page-item'><a class='page-link' href=\"forumList.html?page=$total_page&sort=$sortOrder&search=$searchTerm\" data-abc='true'>끝</a></li>";
          }
          
        ?>