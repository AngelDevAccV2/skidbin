<?php
  session_start();

  include '../classes/dbh.php';

  if (!$_SESSION["rank"] == 1) {
    header("Location: /error.php?status=Prohibited");
    die();
  }

  $userCode = strip_tags($_POST['adPass']);

  if ($_POST) {
    $password = "0245";
    if ($userCode == $password) {
      $_SESSION['admiSet'] = "true";
      header('Location: index.php', true, 303);
    }
    else if ($userCode != $password) {
      header('Location: index.php?status=kys', true, 303);
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Admin Panel</title>
<?php include '../html/head.html' ?>
</head>
<style>
  .reglog-form {
    position:absolute;
    top:50%;
    left:50%;
    width :200px;
    height:200px;
    margin-left:-100px;
    margin-top:-100px;
  }
</style>
<body>
  <?php include '../html/header.php'; ?>
    <?php
      if (!isset($_SESSION['admiSet'])) {
        echo '
        <div class="reglog-form">
        <form action="" method="post">
        <input type="text" name="adPass" placeholder="Admin Code">
        <input type="submit" name="submit" value="Submit" >';
        if ($_GET['status'] == "kys") {
          echo '<p style="color: red; text-align: center;">Incorrect.</p>';
        }
        echo '
        </form>
        </div>
        ';
      }
      else {
        echo '
        <div class="text-center">
        <h1>Admin Panel</h1>
        <a href="pastes.php">Private Pastes List</a><br>
        <a href="https://server149.web-hosting.com:2083/cpsess5761419342/3rdparty/phpMyAdmin/index.php" target="_blank">PhpMyAdmin</a>
        </div>
        ';
      }
    ?>
</body>
</html>