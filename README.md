# NeighborhoodBookshop

OkeyDokey
개발자 지식공유 플랫폼

  

## 기능구현

### 1. 회원가입, 로그인




https://github.com/jieun9999/OkeyDokey/assets/112951633/313dd7c4-ed46-487d-8206-b84dee412cc5



- pw 암호화하기

- 로그인 유지 (세션) 

- 로그인 하지 않은 경우, 게시글, 답변, 댓글 작성이 제한됨 (세션)
  



### 2. 프로필 수정



https://github.com/jieun9999/OkeyDokey/assets/112951633/1100f396-b4e1-4451-b4ef-c95acdeedb03



- 이름, 사진, 비밀번호 변경 가능

- 사진은 jpg, jpeg, png 형식만 업로드 가능 




  


   
### 3. 메인페이지 


https://github.com/jieun9999/OkeyDokey/assets/112951633/55f71841-4a23-466a-bd91-21badff64f67


- 게시글 목록

- 정렬버튼 (최신순/조회순/추천순) 

- 숫자매기기 (페이징) 

- 검색기록 드롭다운 (쿠키)

- 도메인 설정





https://github.com/jieun9999/OkeyDokey/assets/112951633/33c5bb30-53c9-478b-8af2-d843bc8366a2


- 게시글 검색기능




  

   
### 4. 게시글 


https://github.com/jieun9999/OkeyDokey/assets/112951633/3391fc50-c532-4c67-996f-ae813f343e33


- crud

- 게시글 입력창: 유저가 몇글자 까지 입력가능한지 보여줌

- 조회수, 투표 기능, 답변창 토글

- 본인 작성 게시글인 경우에만, 수정/삭제 버튼이 보임

- 게시글 아래에 답변이 있을 경우, 삭제 불가능

- sql injection에 대비한 pdo 처리

- 투표수 브라우저에 저장 (쿠키)




https://github.com/jieun9999/OkeyDokey/assets/112951633/15605583-17e3-4593-93dc-b012a05366c1



- 무한스크롤 (페이징)



   
### 5. 답변 



https://github.com/jieun9999/OkeyDokey/assets/112951633/4bdcfa07-f147-4a8b-9888-37cc60bb351d



- crud

- 답변 입력창: 유저가 몇글자 까지 입력가능한지 보여줌

- 투표 기능, 댓글창 토글

- 본인 작성 답변인 경우에만, 수정/삭제 버튼이 보임

- 답변 아래에 댓글이 있을 경우, 삭제 불가능

- sql injection에 대비한 pdo 처리

- 투표수 브라우저에 저장 (쿠키)

​


### 6. 답변에 대한 댓글 



https://github.com/jieun9999/OkeyDokey/assets/112951633/08bcc7bc-de5d-4157-985e-24bb884b5dd5



- crud

- 댓글 입력창: 유저가 몇글자 까지 입력가능한지 보여줌

- 댓글 더보기 (페이징) : 댓글이 4개이상인 경우에 더보기 버튼을 눌러서, 3개씩 더 로드할 수 있음

- 본인 작성 댓글인 경우에만, 수정/삭제 버튼이 보임

- sql injection에 대비한 pdo 처리




   
### 7.회원탈퇴




https://github.com/jieun9999/OkeyDokey/assets/112951633/193f87ba-439f-43e0-a82c-6b16aceaee0f



- 회원 탈퇴시 로그아웃됨

- 이메일이나 비밀번호 등 개인정보를 NULL로 변경
![스크린샷 2023-12-29 오후 9 41 47](https://github.com/jieun9999/OkeyDokey/assets/112951633/bcd105a6-aa72-40c1-9d72-5be775489e6c)

- 회원이 탈퇴해도 그 회원이 작성한 게시물들은 그대로 남아있음

​
