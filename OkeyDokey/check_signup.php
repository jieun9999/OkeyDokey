<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

// PDO를 계속 사용하려면 MySQLi 관련 코드를 제거함
include 'config_pdo.php';

$username = $_POST['username'];
$signup_email = $_POST['email'];
$signup_pw = $_POST['password'];
$signup_pw_confirm = $_POST['confirm_password'];

//1. 비어 있는 항목이 있는지 검사
if($username == "" || $signup_email == "" || $signup_pw == "" || $signup_pw_confirm == ""){
    echo '<script>alert("비어있는 항목이 있습니다.");</script>';
    echo '<script>history.back();</script>';
}

//2. 비밀번호와 비밀번호 확인이 일치하는지 검사
if($signup_pw != $signup_pw_confirm){
    echo '<script>alert("비밀번호가 일치하지 않습니다.");</script>';
    echo '<script>history.back();</script>';
}

//3.비밀번호 암호화
$password = password_hash($signup_pw, PASSWORD_DEFAULT);

// 4. mysql pdo로 mysql injection을 방어
// 원리: $username이라는 사용자 입력값을 받아서 안전하게 처리한다음, 매개변수인 :username에 넣는다는 소리 
//      (매개변수 바인딩)

try{
    // PDO 객체를 생성
    // 데이터베이스에 연결하는 데 사용됩니다.
    //PDO 연결 문자열에서 공백이 있는 부분을 수정 => 문자열 내에 공백이 있으면 오류를 일으킬 수 있습니다.
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=$dbChar", $user, $pw);

    // 에러 모드 설정
    // PDO::ERRMODE_EXCEPTION로 설정하여 예외(exception)가 발생하면 예외를 던지도록 합니다.
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //5. MySQL에 데이터를 넣고 알림창에 메시지 출력

    // 쿼리 작성
    // ':username', ':email', ':password'는 바인딩할 매개변수 입니다.
    $sql = "INSERT INTO users (userName, userEmail, userPw) VALUES (:username, :email, :password)";

    // PDO Statement 객체 생성
    // PDOStatement 객체를 생성하고, 위에서 작성한 SQL 쿼리를 준비합니다.
    $stmt = $pdo -> prepare($sql);

    // 매개변수 바인딩
    // PDO::PARAM_STR은 문자열 데이터 타입임을 나타냅니다.
    // username 매개변수를 $username 변수에 바인딩합니다. 
    $stmt ->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt ->bindParam(':email', $signup_email, PDO::PARAM_STR);
    $stmt ->bindParam(':password', $password, PDO::PARAM_STR);

    //쿼리 실행
    $stmt ->execute();

    // 성공 메시지 출력
    echo '<script>alert("회원 가입이 완료되었습니다.");</script>';
    echo "<script>location.replace('signin.html');</script>";

}catch(PDOException $ex){
    // 실패 메시지 출력
    echo '<script>alert("회원 가입 중 오류가 발생했습니다.");</script>';
    echo '<script>history.back();</script>';

}finally{
    //PDO 연결 종료
    $pdo = null;
}
?>