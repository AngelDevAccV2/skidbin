<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  if (isset($_GET['id'])){
    if(!intval($_GET['id'])){
        header("Location: error.php?status=Paste not found");
        die();
    }
    $id = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
    $stmt->execute(['id' => strip_tags($id)]);
    $user = $stmt->fetch();

    $uid = strip_tags($user['uid']);
    $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid");
    $stmt2->execute(['uid' => strip_tags($uid)]);
    $user5 = $stmt2->fetch();
    
    if (strip_tags($user['private']) == 1) {
        if ($_SESSION["useruid"] != strip_tags($user['username'])) {
            header("Location: error.php?status=This paste is private.");
            die();
        }
    }

    if (empty(strip_tags($user['id']))) { // messy way of doing it, but it works :)
        header("Location: error.php?status=Paste not found");
        die();
      }
    }
    else {
        header("Location: error.php?status=Missing parameter.");
        die();
    }
?>
    <?php
        try {
            error_reporting(0);
            $uu = $user['title'];
            if(!file_exists("pastes/".$uu.".txt")) {
                header("Location: error.php?status=Paste not found");
                die();
            }
            
        } catch (Exception $e){
            echo "<h1>Issue</h1>";
            die();
        }
    ?>
    <pre><?php include("pastes/".$uu.".txt"); ?></pre>