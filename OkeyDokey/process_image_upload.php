<?php
//이렇게 하면 브라우저에 에러 메시지가 표시됩니다. 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config_mysqli.php';

if(isset($_FILES["image"]["name"])){
  //이미지파일이 폼으로 제출되었는지 확인합니다.

  $id = $_POST["id"];
  $name = $_POST["name"];

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

  if (!in_array($imageExtension, $validImageExtension)){
    //이미지 확장자가 허용된 확장자 목록에 포함되어 있지 않다면,
    echo
    "
    <script>
      alert('유효하지 않은 파일 확장자 입니다. jpg, jpeg, png 파일만 업로드 가능합니다.');
      document.location.href = 'changeProfileImage.php';
    </script>
    ";
  }elseif($imageSize >  1200000){
    echo
    "
    <script>
      alert('이미지 용량이 너무 큽니다. 1.2MB 이하로 줄이세요.');
      document.location.href = 'changeProfileImage.php';
    </script>
    ";
  }else{
    
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
    $query = "UPDATE users SET userImg = '$srcPath' WHERE userId = $id";
    mysqli_query($connect, $query);

    echo '<script>alert("파일이 정상적으로 업로드 되었습니다.")</script>';
    echo '<script>history.back();</script>';

    }else{
        echo "파일 업로드에 실패하였습니다.";
    }
  }

}else{
    echo '<script>alert("서버 파일제출에 문제가 생겼습니다")</script>';
}

?>