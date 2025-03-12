<?php
  session_start();

  if (!isset($_SESSION['useruid'])) {
    header("Location: error.php?status=Prohibited");
    die();
  }

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  $usid = $_GET['uid'];
  $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:usid");
  $stmt->execute(['usid' => strip_tags($_SESSION['userid'])]);
  $user3 = $stmt->fetch();

  $usid2 = $_GET['uid'];
  $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:usid");
  $stmt2->execute(['usid' => strip_tags($usid2)]);
  $user4 = $stmt2->fetch();

  if (strip_tags($user4['users_rank']) == 1) {
    header("Location: error.php?status=Prohibited");
    die();
  }

  if (empty(strip_tags($user4['users_id']))) { // messy way of doing it, but it works :)
    header("Location: error.php?status=User not found");
    die();
  }

  if ($_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
    echo '<p>Authorized.</p>';
  }

  else {
    header("Location: error.php?status=Prohibited");
    die();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Ban <?php echo strip_tags($user4['users_uid']) ?></title>
<?php include 'html/head.html' ?>
</head>
<body>
    <div class="bin-buttons">
      <div class="text-center">
        <form action="" method="post">
          <?php
            if (strip_tags($user4['banned']) == 1) {
              echo '
              <p>This user has been banned.</p>
              <input type="submit" name="submit" value="Unban Account" >
              ';
            }
            else {
              echo '
              <textarea name="banreason" class="comment-text" placeholder="Ban Reason (Max: 100 characters)"></textarea>
              <input type="submit" name="submit" value="Ban Account" >
              ';
            }
          ?>
          <a href="/profile.php?uid=<?php echo strip_tags($user4['users_id']) ?>">Return to users profile</a>
        </form>
      </div>
    </div>
    <div class="bin-text">
    <?php

    if ($_POST) {
      $banRes = htmlentities($_POST['banreason']);
      $usid = $_GET['uid'];
      $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:usid");
      $stmt->execute(['usid' => strip_tags($_SESSION['userid'])]);
      $user3 = $stmt->fetch();

      $usid2 = $_GET['uid'];
      $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:usid");
      $stmt2->execute(['usid' => strip_tags($usid2)]);
      $user4 = $stmt2->fetch();

      if (strip_tags($user4['banned']) == 1) {
        $banRes = "";
        $sql = "UPDATE users SET banned = 0, ban_reason = :banres, banned_by = :bannedby WHERE users_id=:id"; 
        $result = $dbh->prepare($sql);
            $values = array(':banres'           => $banRes,
                            ':bannedby'         => "",
                            ':id'               => strip_tags($user4['users_id'])
            );
            $res = $result->execute($values);
            header('Location: deleteac.php?uid='.strip_tags($user4['users_id']).'', true, 303);
            die();
      }

      else {
        if (empty($banRes)) {
          header('Location: error.php?status=Input cannot be empty.', true, 303);
          die();
        }
        if (strlen($banRes) > 100) {
          header('Location: error.php?status=Input is over the character limit.', true, 303);
          die();
        }
        $sql = "UPDATE users SET banned = 1, ban_reason = :banres, banned_by = :bannedby WHERE users_id=:id"; 
        $result = $dbh->prepare($sql);
            $values = array(':banres'           => $banRes,
                            ':bannedby'         => strip_tags($_SESSION['useruid']),
                            ':id'               => strip_tags($user4['users_id'])
            );
            $res = $result->execute($values);
            header('Location: deleteac.php?uid='.strip_tags($user4['users_id']).'', true, 303);
            die();
      }

      if (!$_SESSION["rank"] == 1 || !$_SESSION["rank"] == 4) {
        session_start();
        session_unset();
        session_destroy();
      }    
      echo '<p>'.strip_tags($user4['users_uid']).' successfully banned.</p>';
    }

    ?>
    </div>
</body>
</html>