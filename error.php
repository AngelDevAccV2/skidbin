<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  // ALL ERRORS:

  /*

  prohibited
  pasteisempty
  pasteisshort
  pastetitletoolong
  stmtfailed
  emptyinput
  username
  passwordmatch
  useroremailtaken
  missingparam
  usernotfound
  pastenotfound
  privatepaste

  */
?>




<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Error</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <script src="/assets/js/snow.js"></script> christmas is over >:) -->
<?php

if (!isset($_GET['status'])) {
  header("Location: index.php");
}

?>
<body>
  <div class="text-center" style="color: black;">
    <h1>Error Reached</h1>
    <h4><?php echo htmlentities($_GET['status']); ?></h4>
    <a type="button" class="btn btn-default" href="/">Back to SkidBin</a>
  </div>
  <?php 
      /*if ($_GET["status"] == "emptyinput") {
        echo '<p>Please fill in all boxes.</p>';
      }
      else if ($_GET["status"] == "pasteisempty") {
        echo '<p>Your submitted paste is empty.</p>';
      }
      else if ($_GET["status"] == "pastenotfound") {
        http_response_code(404);
        echo '<p>This paste does not exist, or it was deleted.</p>';
        echo '<img src="/assets/httpcats/404.jpg">';
      }
      else if ($_GET["status"] == "usernotfound") {
        http_response_code(404);
        echo '<p>User does not exist.</p>';
        echo '<img src="/assets/httpcats/404.jpg">';
      }
      else if ($_GET["status"] == "missingparam") {
        echo '<p>You are missing a parameter in the URL.</p>';
      }
      else if ($_GET["status"] == "privatepaste") {
        echo '<p>This paste is private.</p>';
      }
      else if ($_GET["status"] == "useroremailtaken") {
        echo '<p>This username or email is already in use.</p>';
      }
      else if ($_GET["status"] == "pwsd") {
        echo '<p>Passwords do not match.</p>';
      }
      else if ($_GET["status"] == "username") {
        echo '<p>Username does not exist.</p>';
      }
      else if ($_GET["status"] == "stmtfailed") {
        http_response_code(503);
        echo '<p>A database error occured.</p>';
        echo '<img src="/assets/httpcats/503.jpg">';
      }
      else if ($_GET["status"] == "pastetitletoolong") {
        echo '<p>Your paste title is too long.</p>';
      }
      else if ($_GET["status"] == "pasteisshort") {
        echo '<p>Your paste is too short.</p>';
      }
      else if ($_GET["status"] == "captchafail") {
        echo '<p>Captcha not solved.</p>';
      }
      else if ($_GET["status"] == "titletaken") {
        echo '<p>This paste title is already in use.</p>';
      }
      else if ($_GET["status"] == "prohibited") {
        echo '<p>Action not allowed.</p>';
      }
      else if ($_GET["status"] == "toolong") {
        echo '<p>Username or email too big.</p>';
      }
      else if ($_GET["status"] == "pasteshort") {
        echo '<p>Paste is too short.</p>';
      }
      else if ($_GET['status'] == "limit") {
        echo '<p style="text-color:red;">Ban reason must be under 100 characters.</p>';
      }
      else if ($_GET['status'] == "empty") {
        echo '<p style="text-color:red;">Ban reason must not be empty.</p>';
      }*/
  ?>
</body>
</html>