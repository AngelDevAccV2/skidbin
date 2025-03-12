<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /login.php");
    exit();
}

include_once '../classes/dbh.php';

if (isset($_GET['comment_id'])) {
    $deleteComment = $dbh->prepare("DELETE FROM profile_comments WHERE id = :comment_id");
    $deleteComment->execute(['comment_id' => $_GET['comment_id']]);

    header("Location: /profile.php?uid=".$_SESSION['userid']);
    exit();
} else {
    header("Location: /error.php?status=Comment ID not provided");
    exit();
}
?>
