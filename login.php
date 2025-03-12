<?php
session_start();

include 'classes/dbh.php';
include 'includes/purify.inc.php';
include 'html/ban_check.php';

if (isset($_SESSION['useruid'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SkidBin - Login</title>
    <?php include 'html/head.html' ?>
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="text-center">
        <h1>Login</h1>
    </div>
    <div class='reglog-form'>
            <form action="includes/login.inc.php" method="post">
            <label for="username">Username</label><br>
            <input type="text" name="uid" placeholder="Username" value="<?php echo isset($_POST['uid']) ? test_input($_POST['uid']) : ''; ?>"><br>
            <label for="pwd">Password</label><br>
            <input type="password" name="pwd" placeholder="Password" value="<?php echo isset($_POST['pwd']) ? test_input($_POST['pwd']) : ''; ?>"><br>
            
            <div class="h-captcha" data-sitekey="ca809027-c3cb-42b6-9dd8-31d626eaa472"></div>
            <br>
            
            <input type="submit" name="submit" value="Login">
            
            <?php
            if (isset($_GET["status"]) && $_GET["status"] == "captcha_fail") {
                echo '<p style="color: red;">Complete the captcha</p>';
            }
            if (isset($_GET["status"]) && $_GET["status"] == "incorrect_password") {
                echo '<p style="color: red;">Incorrect password.</p>';
            }
            if (isset($_GET["status"]) && $_GET["status"] == "banned") {
                echo '<p style="color: red;">You have been banned from SkidBin.</p>';
            }
            ?>
        </form>
    </div>
</body>
</html>
