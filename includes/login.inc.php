<?php
session_start();

if (isset($_POST['submit'])) {
    
    $hCaptchaSecret = 'ES_ffa43c35f6b14412ade25f0be97e4fec';
    $hCaptchaResponse = $_POST['h-captcha-response'];
    
    $response = file_get_contents("https://hcaptcha.com/siteverify?secret=$hCaptchaSecret&response=$hCaptchaResponse");
    $responseData = json_decode($response);

    if (!$responseData->success) {
       
        header("Location: ../login.php?status=captcha_fail");
        exit();
    }

    
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    include "../classes/dbh.classes.php";
    include "../classes/login.classes.php";
    include "../classes/login-contr.classes.php";
    
    $login = new LoginContr($uid, $pwd);
    $loginResult = $login->loginUser();

    if ($loginResult === "incorrect_password") {
        
        header("Location: ../login.php?status=incorrect_password");
        exit();
    } elseif ($_SESSION["banned"] == 1) {
        // User is banned
        session_unset();
        session_destroy();
        header("Location: ../login.php?status=banned");
        exit();
    }

   
    header("Location: ../index.php");
    exit();
} else {
    
    header("Location: ../login.php");
    exit();
}
?>
