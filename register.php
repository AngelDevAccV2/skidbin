<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
  include 'includes/purify.inc.php';
  
    if (isset($_SESSION['useruid'])) {
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Register</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="text-center">
        <h1>Register</h1>
        <p>Please use a randomly generated password.</p>
    </div>
    <div class='reglog-form'>
        <form action="includes/signup.inc.php" method="post">
            <label for="username">Username</label>
            <br>
            <input type="text" name="uid" placeholder="Username" value="<?php echo isset($_POST['uid']) ? test_input($_POST['uid']) : ''; ?>">
            <label for="email">This should mainly be used for emails however feel free to put whatever</label>
            <br>
            <input type="text" name="email" placeholder="Email/XMPP" value="<?php echo isset($_POST['email']) ? test_input($_POST['email']) : ''; ?>">
            <label for="pwd">Password [ DONT FORGET ]</label>
            <br>
            <input type="password" name="pwd" placeholder="Password" value="<?php echo isset($_POST['pwd']) ? test_input($_POST['pwd']) : ''; ?>">
            <br>
            <label for="confirmpwd">Repeat Password</label>
            <br>
            <input type="password" name="confirmpwd" placeholder="Repeat Password" value="<?php echo isset($_POST['confirmpwd']) ? test_input($_POST['confirmpwd']) : ''; ?>">
            <br>
            <!--<div class="h-captcha" data-sitekey="51a73684-2376-48f4-814d-300916b065df"></div>-->
            <div class="h-captcha" data-sitekey="ca809027-c3cb-42b6-9dd8-31d626eaa472"></div>
            <input type="submit" name="submit" value="Register" >
            <?php
                if (isset($_GET["error"]) && $_GET["error"] == "captchafail") {
                    echo '<p style="color: red;">Captcha not solved.</p>';
                }
            ?>
            <p>Please make sure your username does not contain any special characters or spaces.</p>
            <p>We do no validation on emails/XMPP addresses, so feel free to enter whatever you want.</p>
            <p>Please Remember you're passwords if you forget it you might have to pay a fine to get it reset.</p>
        </form>
    </div>
</body>
</html>