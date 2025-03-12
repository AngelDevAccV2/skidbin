<?php

class Login extends Dbh {

    protected function getUser($uid, $pwd) {
        $stmt = $this->connect()->prepare('SELECT users_pwd FROM users WHERE users_uid = ? OR users_email=? AND users_rank=0;');

        if(!$stmt->execute(array($uid, $pwd))) {
            $stmt = null;
            header('location: ../error.php?status=SQL statement failed');
            exit();
        }

        if($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: ../error.php?status=User not found");
            exit();
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["users_pwd"]); // idk what this does, it just does it's job
        if($checkPwd == false) {
            $stmt = null;
            header("location: ../error.php?status=Incorrect password");
            exit();
        } elseif($checkPwd == true) {
            $stmt = $this->connect()->prepare('SELECT * FROM users WHERE users_uid = ? OR users_email = ? AND users_pwd = ?;');

            if(!$stmt->execute(array($uid, $uid, $pwdHashed[0]['users_pwd']))) {
                $stmt = null;
                header("location: ../error.php?status=SQL statement failed");
                exit();
            }

            if($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: ../error.php?status=User not found");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ini_set('session.cookie_secure', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            session_start();
            $_SESSION["userid"] = $user[0]["users_id"];
            $_SESSION["useruid"] = $user[0]["users_uid"];
            $_SESSION["rank"] = $user[0]["users_rank"];
            $_SESSION["banned"] = $user[0]["banned"]; // used for initial login
        }

        $stmt = null;
    }


}