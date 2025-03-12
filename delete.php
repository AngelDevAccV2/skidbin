<?php
session_start();

include 'classes/dbh.php';
include 'html/ban_check.php';

function flagPaste($dbh, $id, $creator_id, $name, $data, $reason) {
  $stmt = $dbh->prepare("INSERT INTO flagged_pastes (name, creator_id, data, reason) VALUES (?, ?, ?, ?)");
  $stmt->bindParam(1, $name);
  $stmt->bindParam(2, $creator_id);
  $stmt->bindParam(3, $data, PDO::PARAM_LOB);
  $stmt->bindParam(4, $reason);
  return $stmt->execute();
}

$allowed_ranks = [1, 6, 4];
if (!isset($_SESSION["rank"]) || !in_array($_SESSION["rank"], $allowed_ranks)) {
    header("Location: error.php?status=Prohibited");
    exit();
}

if (isset($_GET['pid']) && isset($_GET['reason'])) {
  $id = intval($_GET['pid']);
  $reason = $_GET['reason'];

  $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
  $stmt->execute(['id' => $id]);
  $user = $stmt->fetch();

  if (!$user) {
      header("Location: error.php?status=Prohibited");
      exit();
  }

  $uid = intval($user['uid']);

  $allowed_permissions = [1, 2, 4, 5, 6];
  if (!in_array($_SESSION["rank"], $allowed_permissions) || ($_SESSION["useruid"] != $user['username'] && $_SESSION["rank"] != 1 && $_SESSION["rank"] != 4 && $_SESSION["rank"] != 6 && $_SESSION["rank"] != 5)) {
      header("Location: error.php?status=Prohibited");
      exit();
  }

  flagPaste($dbh, $id, $uid, $user["title"], file_get_contents('pastes/' . $user["title"] . '.txt'), $reason);

  $stmt = $dbh->prepare("DELETE FROM doxes WHERE id=:id");
  $stmt->execute(['id' => $id]);
  $stmtCom = $dbh->prepare("DELETE FROM comments WHERE com_paste=:id");
  $stmtCom->execute(['id' => $id]);
  $sql = "UPDATE users SET pastes = pastes - 1 WHERE users_id=:id";
  $result = $dbh->prepare($sql);
  $result->execute(['id' => $uid]);
  $pasteDelete = 'pastes/' . $user["title"] . '.txt';
  if (unlink($pasteDelete)) {
      $paste_message = '<p>Paste deleted. ' . $pasteDelete . '</p>';
  } else {
      $paste_message = '<p>Paste deletion failed. ' . $pasteDelete . '</p>';
  }
  $paste_message .= '<a href="index.php">Homepage</a>';
} else {
    header("Location: error.php?status=Paste ID or Reasoning not provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Deletion Panel</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <div class="bin-buttons">
    </div>
    <div class="bin-text">
        <?php echo $paste_message; ?>
    </div>
</body>
</html>