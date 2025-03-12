<?php

session_start();

include '../classes/dbh.php';
include '../html/ban_check.php';

if (isset($_GET['uid'])) {
    if (isset($_SESSION['useruid'])) {
        $id = strip_tags($_GET['uid']);
        $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (empty(strip_tags($user['users_uid']))) { // messy way of doing it, but it works :)
            header("Location: ../error.php?status=User not found");
            die();
        }

        if (strip_tags($user['users_rank']) == 1) {
            if ($_SESSION["userid"] != strip_tags($user['users_id'])) {
                header("Location: ../error.php?status=Prohibited");
                die();
            }
        }

        if ($_SESSION['rank'] == 1 || $_SESSION['rank'] == 4 || $_SESSION['rank'] == 5 || $_SESSION['rank'] == 6) {
            if ($_SESSION["useruid"] == strip_tags($user['users_uid']) || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) { // I want to kill myself
                if(isset($_POST["submit"])) {
                    $uuid = strip_tags($_POST['newusername']);

                    if (strip_tags(strlen($uuid)) > 15) {
                        header("Location: ../settings.php?uid=".$user['users_id']."&status=toolongusr");
                        die();
                    }

                    if(!preg_match("/^[a-zA-Z0-9]*$/", $_POST['newusername'])){
                        header("location: ../error.php?status=Your username must not contain any special characters.");
                        exit();
                    }

                    $stmtCheck = $dbh->prepare('SELECT users_uid FROM users WHERE users_uid = :newuid;');
                    $stmtCheck->execute(['newuid' => $uuid]);
                    $resultcheck;

                    if($stmtCheck->rowCount() > 0) {
                        $resultcheck = false;
                    } else {
                        $resultcheck = true;
                    }

                    if ($resultcheck == false) {
                        header("location: ../error.php?status=Username taken");
                        exit();
                    }

                    $sql = "UPDATE users SET users_uid = :newuid WHERE users_id=:iid"; 
                    $result = $dbh->prepare($sql);
                        $values = array(':newuid'           => $uuid,
                                        ':iid'              => strip_tags($user['users_id'])
                                        );
                        $res = $result->execute($values);
                    if ($_SESSION["useruid"] == strip_tags($user['users_uid'])) {
                        $_SESSION["useruid"] = strip_tags($user["users_uid"]);
                    }
                    header('Location: ../settings.php?uid='.$id.'');
                }
            }
        }
        else {
            header("Location: ../error.php?status=Prohibited");
        }
    }
    else {
        echo '<p>fuck off</p>';
    }
}
else {
    header("Location: ../error.php?status=Missing parameter");
    die();
}