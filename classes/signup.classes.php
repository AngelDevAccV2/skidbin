<?php

class Signup extends Dbh {

    protected function setUser($uid, $pwd, $email) {
        $stmt = $this->connect()->prepare('INSERT INTO users (users_id, users_uid, users_pwd, users_email) VALUES (:user_id, :user_uid, :user_pwd, :user_email);');

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        $stmt2 = $this->connect()->prepare('SELECT MIN(st1.users_id + 1) as uCount FROM users as st1 LEFT JOIN users as st2 ON st2.users_id = st1.users_id + 1 WHERE st2.users_id IS NULL');
        $stmt2->execute();
        $userNewIdRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        $userCountLast = $userNewIdRow['uCount'];

        if($userCountLast == "" || $userCountLast == 0){
            $stmt3 = $this->connect()->prepare('SELECT COUNT(`users_id`) as u_count FROM `users`');
            $stmt3->execute();
            $userCountRow = $stmt3->fetch(PDO::FETCH_ASSOC);
            $userCountLast = $userCountRow['u_count']+1;
        }

        $stmt->bindParam(':user_id', $userCountLast);
        $stmt->bindParam(':user_uid', $uid);
        $stmt->bindParam(':user_pwd', $hashedPwd);
        $stmt->bindParam(':user_email', $email);

        if(!$stmt->execute()) {
            $stmt = null;
            header('location: ../error.php?status=SQL statement failed');
            exit();
        }
        $stmt = null;
    }

    // check if the username or email are already in use
    protected function checkUser($uid, $email) {
        $stmt = $this->connect()->prepare('SELECT users_uid FROM users WHERE users_uid = :user_uid OR users_email = :user_email;');
        $stmt->bindParam(':user_uid', $uid);
        $stmt->bindParam(':user_email', $email);

        if(!$stmt->execute()) {
            $stmt = null;
            header("location: ../error.php?status=SQL statement failed");
            exit();
        }

        $resultcheck;
        if($stmt->rowCount() > 0) {
            $resultcheck = false;
        } else {
            $resultcheck = true;
        }
        return $resultcheck;
    }
}