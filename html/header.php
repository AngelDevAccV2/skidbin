<?php

if (isset($_SESSION['useruid'])) {
  $id = $_SESSION['userid'];
  $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
  $stmt->execute(['id' => strip_tags($id)]);
  $user2 = $stmt->fetch();

  $banId = $_SESSION['userid'];
  $stmtBanChk = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
  $stmtBanChk->execute(['id' => strip_tags($banId)]);
  $userBanChk = $stmtBanChk->fetch();

  if (strip_tags($userBanChk['banned']) == 1) {
    session_unset();
    session_destroy();
    header("location: ../login.php?status=banned");
    die();
  }
  
}
?>



<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/"><span style="color: #F92242">SKID<span style="color: #FFFFFF">BIN</a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li><a href="/post.php"><span class="glyphicon glyphicon-paste"></span> New Paste</a></li>
        <li><a href="/users.php"><span class="glyphicon glyphicon-user"></span> Users</a></li>
        <li><a href="/upgrade.php"><span class="glyphicon glyphicon-tag"></span> Upgrades</a></li>
        <li><a href="/wallofclowns.php"><span class="glyphicon glyphicon-pawn"></span> Wall of Clowns</a></li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="/">About
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="/faq.php"><span class="glyphicon glyphicon-check"></span> FAQ</a></li>
          <li><a href="/viewpaste.php?id=55038"><span class="glyphicon glyphicon-book"></span> ToS</a></li>
          <li><a href="/viewpaste.php?id=29175"><span class="glyphicon glyphicon-refresh"></span> Changelog</a></li>
          <li><a href="/donate.php"><span class="glyphicon glyphicon-bitcoin"></span> Donate</a></li>
          <li><a href="/prices.php"><span class="glyphicon glyphicon-tags"></span> Prices</a></li>
          <li><a href="/contact.php"><span class="glyphicon glyphicon-envelope"></span> Contact</a></li>
          </ul>
          </li>
        <?php
          if (isset($_SESSION["useruid"])) {      
            if ($_SESSION["rank"] == 1 || $_SESSION["rank"] == 3) {
              echo '<li><a href="/admin/"><span class="glyphicon glyphicon-lock"></span> Admin Panel</a></li>';
            }
            if ($_SESSION["rank"] == 1 || $_SESSION["rank"] == 3 || $_SESSION["rank"] == 4 || $_SESSION["rank"] == 5 || $_SESSION["rank"] == 6) {
              echo '<li><a href="/modcp.php"><span class="glyphicon glyphicon-lock"></span> Mod CP</a></li>';
            }
          }
        ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <?php
        if (!isset($_SESSION["useruid"])) {
          echo '<li><a href="/register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>';
          echo '<li><a href="/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
        }
        else {
          echo '<li><a href="/profile.php?uid='.strip_tags($user2["users_uid"]).'"><span class="glyphicon glyphicon-user"></span> Profile</a></li>';
          echo '<li><a href="/includes/logout.inc.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
        }

      ?>
    </ul>
    </div>
</nav> 
