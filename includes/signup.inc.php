<?php

if(isset($_POST["submit"])) 
{
    // grab the data from signup form
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $confirmpwd = $_POST["confirmpwd"];
    $email = $_POST["email"];

    $data = array (
        'secret' => "ES_ffa43c35f6b14412ade25f0be97e4fec",
        'response' => $_POST['h-captcha-response']
    );

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);
    if($responseData->success) {
        if (strip_tags(strlen($uid)) > 15) {
            header("Location: ../error.php?status=Username too long (Max: 15)");
            die();
        } 
        if (strip_tags(strlen($email)) > 30) {
            header("Location: ../error.php?status=Email too long (Max: 30)");
            die();
        } 
        // instantiate signupContr class
        include "../classes/dbh.classes.php";
        include "../classes/signup.classes.php";
        include "../classes/signup-contr.classes.php";
        $signup = new SignupContr($uid, $pwd, $confirmpwd, $email);
        // running error handlers and user signup
        $signup-> signupUser();
        // going back to front page
        header("location: ../login.php?status=success");
    } 
    else {
        header("Location: ../register.php?error=captchafail");
    }
}
else {
    echo '<p>fuck off</p>';
}