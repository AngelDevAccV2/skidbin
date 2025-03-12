<?php
session_start();

include 'classes/dbh.php';
include 'html/ban_check.php';

function transferOwnership($dbh, $pasteId, $newUsername) {
    if(empty($pasteId) || empty($newUsername) || !$dbh) {
        return "Invalid input parameters.";
    }

    $stmt = $dbh->prepare("SELECT users_id FROM users WHERE users_uid = ?");
    $stmt->execute([$newUsername]);
    $u_id = $stmt->fetchColumn();

    if(!$u_id) {
        return "New username not found.";
    }


    $stmt = $dbh->prepare("UPDATE doxes SET username = ?, uid = ? WHERE id = ?");
    $stmt->execute([$newUsername, $u_id, $pasteId]);

    if($stmt->rowCount() > 0) {
        return "Ownership transferred successfully.";
    } else {
        return "Failed to transfer ownership. Make sure the paste ID and current username are correct.";
    }
}

$allowed_ranks = [1, 6, 4];
if (!isset($_SESSION["rank"]) || !in_array($_SESSION["rank"], $allowed_ranks)) {
    header("Location: error.php?status=Prohibited");
    exit();
}

if (isset($_GET['uid']) && isset($_GET['pid'])) {
  $uid = $_GET['uid'];
  $pid = intval($_GET['pid']);

  $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
  $stmt->execute(['id' => $pid]);
  $user = $stmt->fetch();

  if (!$user) {
      header("Location: error.php?status=Prohibited");
      exit();
  }

  $allowed_permissions = [1, 2, 4, 5, 6];
  if (!in_array($_SESSION["rank"], $allowed_permissions) || ($_SESSION["useruid"] != $user['username'] && $_SESSION["rank"] != 1 && $_SESSION["rank"] != 4 && $_SESSION["rank"] != 6 && $_SESSION["rank"] != 5)) {
      header("Location: error.php?status=Prohibited");
      exit();
  }

  echo transferOwnership($dbh, $pid, $uid);
}