<?php
  if (isset($_SESSION['useruid'])) {
    $banId = $_SESSION['userid'];
    $stmtBanChk = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
    $stmtBanChk->execute(['id' => strip_tags($banId)]);
    $userBanChk = $stmtBanChk->fetch();
  
    if (strip_tags($userBanChk['banned']) == 1) {
      session_unset();
      session_destroy();
      header("location: login.php?status=banned");
      die();
    }
  }
?>