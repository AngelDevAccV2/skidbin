<?php

session_start();

include '../classes/dbh.php';
include '../html/ban_check.php';

if (isset($_SESSION['useruid'])) {
    $id = strip_tags($_GET['cid']);
    $stmt = $dbh->prepare("SELECT * FROM comments WHERE com_id=:id");
    $stmt->execute(['id' => strip_tags($id)]);
    $com = $stmt->fetch();

    $uid = $com['com_author'];
    $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_uid = :uuid");
    $stmt2->execute(['uuid' => strip_tags($uid)]);
    $user = $stmt2->fetch();
    $countt = $stmt2->rowCount();

    $pasteID = $com['com_paste'];

    if (empty(strip_tags($com['com_author']))) { // messy way of doing it, but it works :)
        header("Location: ../index.php");
        die();
    }

    if ($_SESSION['rank'] == 6 || $_SESSION['rank'] == 4 || $_SESSION['rank'] == 1 || $_SESSION['rank'] == 5) {
        if ($_SESSION['useruid'] == strip_tags($com['com_author']) || $_SESSION['rank'] == 4 || $_SESSION['rank'] == 1 || $_SESSION['rank'] == 6 || $_SESSION['rank'] == 5) {
            $stmtD = $dbh->prepare("DELETE FROM comments WHERE com_id=:id");
            $stmtD->execute(['id' => strip_tags($id)]);
            $comD = $stmt->fetch();

            if ($_SERVER['HTTP_REFERER'] == 'https://flamebin.com/modcp.php?action=comments' || $_SERVER['HTTP_REFERER'] == 'http://flame5r2arsockf26j37uh4o5zcgyy7pttmusfyb4xazcam6gowpc4id.onion/modcp.php?action=comments') {
                header('Location: ../modcp.php');
            }
            else {
                header('Location: ../viewpaste.php?id='.$pasteID.''); // return user to paste
            }
        }
        else {
            header("Location: ../error.php?status=Prohibited");
            die();
        }
    }
    else {
        header("Location: ../error.php?status=Prohibited");
        die();
    }
}
else {
    header("Location: ../error.php?status=Prohibited");
    die();
}