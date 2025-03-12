<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: /login.php");
    exit();
}

include_once '../classes/dbh.php';

if ($_SESSION['rank'] == 2 || $_SESSION["rank"] == 3 || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET['userid']) && isset($_GET['rank'])) {
            $selectedUserID = htmlspecialchars($_GET['userid']);
            $selectedRank = htmlspecialchars($_GET['rank']);

            if ($_SESSION['rank'] <= $selectedRank) {
                $updateRank = $dbh->prepare("UPDATE users SET users_rank = :rank WHERE users_id = :user_id");
                $updateRank->execute(['rank' => $selectedRank, 'user_id' => $selectedUserID]);

                if ($updateRank) {
                    header("Location: /profile.php?uid=".$selectedUserID);
                    exit();
                } else {
                    header("Location: /error.php?status=Error updating rank");
                    exit();
                }
            } else {
                header("Location: /error.php?status=Insufficient authorization.");
                exit();
            }
        }
    }
} else {
    header("Location: /error.php?status=Unauthorized!");
    exit();
}

?>