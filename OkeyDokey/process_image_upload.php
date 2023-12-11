<?php
//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_name('로그인세션');
session_start(); //세션시작

include 'config_mysqli.php';

// 서버로 들어오는 모든 데이터 : id, image, username, currentPassword, newPassword, confirmPassword

$id = isset($_POST["id"])? $_POST["id"] : null;
$name = isset($_POST["userName"])? $_POST["userName"] : null;
$image = isset($_POST['["image"]'])? $_POST['["image"]'] :null;
$currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : null;
$newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : null;
$confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword']: null;

// 1. 사진 업데이트 할지 말지 결정
// 2. 이름 업데이트 할지 말지 결정
// 3. 비번 업데이트 할지 말지 결정

if(isset($_FILES["image"]["name"])){
  //양식 제출 시 "image"라는 이름의 파일이 포함되어 있는지 확인하는 중입니다.

  $imageName = $_FILES["image"]["name"]; 
  //업로드된 파일의 원래 이름을 가져와 변수 $imageName에 할당합니다.

  $imageSize = $_FILES["image"]["size"];
  //업로드된 파일의 크기를 가져와 변수 $imageSize에 할당합니다.

  //임시 저장 파일명
  $tmpFile = $_FILES["image"]["tmp_name"];
  //업로드된 파일이 임시로 저장된 경로를 가져와 변수 $tmpFile에 할당합니다.

  // Image validation
  $validImageExtension = ['jpg', 'jpeg', 'png']; 
  //허용된 이미지 확장자를 나타내는 배열을 생성합니다.
  $imageExtension = explode('.', $imageName);
  //파일 이름에서 확장자 부분을 추출하기 위해 파일 이름을 점 (.)을 기준으로 나눕니다.
  $imageExtension = strtolower(end($imageExtension));
  //추출된 확장자를 소문자로 변환하고, 변수 $imageExtension에 할당합니다.



  // 1. 사진 업데이트 할지 말지 결정
  if (!empty($imageSize) && !in_array($imageExtension, $validImageExtension)){
    // 이미지 파일이 존재하고, 이미지 확장자가 허용된 확장자 목록에 포함되어 있지 않을때
    echo
    "
    <script>
      alert('유효하지 않은 파일 확장자 입니다. jpg, jpeg, png 파일만 업로드 가능합니다.');
      document.location.href = 'changeProfileImage.php';
    </script>
    ";

  }elseif(!empty($imageSize) && $imageSize >  1200000){
    // 이미지 파일이 존재하지만, 파일 용량이 클때
    echo
    "
    <script>
      alert('이미지 용량이 너무 큽니다. 1.2MB 이하로 줄이세요.');
      document.location.href = 'changeProfileImage.php';
    </script>
    ";
  }elseif(!empty($imageSize)){
    // 이미지 파일이 존재하고, 이미지 변경에 적합할때

    // 임시 파일 옮길 디렉토리 및 파일명 
    $resFile = __DIR__ . "/img/{$imageName}";

    // 임시 저장된 파일을 나의 디렉토리 및 파일명으로 옮김
    // 이것은 사용자가 프로필 이미지를 업로드하면, 그 이미지를 내 서버에 저장하기 위함입니다.
    $imageUpload = move_uploaded_file($tmpFile, $resFile);

    //src로 띄워줄 사진 경로
    // ip 주소 + 현재 경로
    $srcPath = "http://52.79.41.79/OkeyDokey/img/{$imageName}";

    // 업로드 성공여부 확인
    if($imageUpload == true){

    //쿼리 생성
    //파일명을 새로 만드는 건 알겠는데, 그걸 그냥 그대로 db에 넣는게 아니라, 
    //앞에 ip 주소 같은 경로를 붙여서 update 쿼리를 작성해야 할것 같아. 그래야 로컬에서 올린 이미지가 웹 주소를 갖게 되는 것
    $queryImg = "UPDATE users SET userImg = '$srcPath'
              WHERE userId = $id";

    mysqli_query($connect, $queryImg);

    }
  }

  // 2. 이름 업데이트 할지 말지 결정
  if(!empty($name)){
    $queryName = "UPDATE users SET userName = '$name'
    WHERE userId = $id";

    mysqli_query($connect, $queryName);

    //세션의 이름을 변경해줌
    $_SESSION['userName'] = $name;
  }



    // 1. 만약 Current Password가 비었다면, 그냥 빼고 쿼리 업데이트를 한다
    // 2. 만약 Current Password가 존재한다면, 세션아이디의 비밀번호와 같은지 확인한다
      // 2-1. 같다면, New Password 와 Confirm Password 같은지 검사한다
        //2-1-1. 같다면, New Password를 update 쿼리를 사용해서 변경함 + "프로필이 정상적으로 변경되었습니다." 라는 알림창
        //2-1-2. 다르면, '새로운 비밀번호를 다시 확인하세요' 라는 알림창

    // 2-2. 다르면, '입력한 비밀번호가 현재 비밀번호와 일치하지 않습니다' 라는 알림창


  // 3. 비번 업데이트 할지 말지 결정
  if($currentPassword && $newPassword && $confirmPassword){
  
   // 세션 아이디에 해당하는 users 테이블의 행을 가져온다
   $sqlUser = "SELECT * FROM users WHERE userId = {$_SESSION['userId']}"; 
   $result = $connect ->query($sqlUser);
   $row = mysqli_fetch_assoc($result);

  if($result -> num_rows == 1 && password_verify($currentPassword, $row['userPw'])){

    if($newPassword == $confirmPassword){

      //3.비밀번호 암호화
      $hasedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

      $queryPassword = "UPDATE users SET userPw = '$hasedPassword'
      WHERE userId = $id";
  
      mysqli_query($connect, $queryPassword);

    }else{
    echo '<script>alert("새로운 비밀번호를 다시 확인하세요.")</script>';
    echo '<script>history.back();</script>';

    }

  }else{

    echo '<script>alert("입력한 비밀번호가 현재 비밀번호와 일치하지 않습니다.")</script>';
    echo '<script>history.back();</script>';
  }
    
  }
     echo '<script>alert("프로필이 정상적으로 변경되었습니다.")</script>';
     echo '<script>history.back();</script>';

    }
  
else{
    echo '<script>alert("양식 제출 시 "image"라는 이름의 파일이 포함되어 있지 않습니다")</script>';

}

?>