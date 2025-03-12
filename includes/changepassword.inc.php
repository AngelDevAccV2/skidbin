<?php
session_start();

include 'classes/dbh.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['uid'])) {
    $id = $_GET['uid'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

   
    $stmt = $dbh->prepare("SELECT users_pwd FROM users WHERE users_id = :id");
    $stmt->execute(['id' => $id]);
    $userData = $stmt->fetch();

    
    if ($userData && password_verify($currentPassword, $userData['users_pwd'])) {
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        
        $sql = "UPDATE users SET users_pwd = :password WHERE users_id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute(['password' => $hashedPassword, 'id' => $id]);

        
        header("Location: /settings.php?uid={$id}&status=passwordchanged");
        exit();
    } else {
        
        header("Location: /settings.php?uid={$id}&status=passworderror");
        exit();
    }
} else {
   
    header("Location: /error.php?status=Invalid request");
    exit();
}
?>
