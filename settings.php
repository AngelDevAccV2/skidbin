<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

include 'classes/dbh.php';
include 'html/ban_check.php';

$profilebkgstatus = "False\n";

if (!isset($_SESSION['useruid'])) {
    header("Location: error.php?status=Prohibited");
    exit();
}

if (isset($_GET['uid'])) {
    $id = $_GET['uid'];

    $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
    $stmt->execute(['id' => strip_tags($id)]);
    $user = $stmt->fetch();

    if (empty($user)) {
        header("Location: error.php?status=User not found");
        exit();
    }

   
    if ($_SESSION["useruid"] != strip_tags($user['users_uid']) && $_SESSION["rank"] != 1 && $_SESSION['rank'] != 4) {
        header("Location: error.php?status=Prohibited");
        exit();
    }
} else {
    header("Location: error.php?status=Missing parameter");
    exit();
}
echo isset($_FILES['background']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['img'])) {
    $hcaptcha_secret = 'ES_ffa43c35f6b14412ade25f0be97e4fec';
    $response = $_POST['h-captcha-response'];
    $verify_url = 'https://hcaptcha.com/siteverify';
    $data = array(
        'secret' => $hcaptcha_secret,
        'response' => $response
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($verify_url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check === false) {
            header("Location: settings.php?uid={$id}&status=pfpfile");
            exit;
        }

        if ($_FILES["img"]["size"] > 2000000) { // 2MB
            header("Location: settings.php?uid={$id}&status=pfpsize");
            exit;
        }

        $allowed_extensions = array("jpg", "jpeg", "gif", "png");
        if (!in_array($imageFileType, $allowed_extensions)) {
            header("Location: settings.php?uid={$id}&status=pfpext");
            exit;
        }

        $unique_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            $user_id = strip_tags($user['users_id']);
            $sql = "UPDATE users SET profileimg = :profileimg WHERE users_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':profileimg', $target_file);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            header("Location: settings.php?uid={$id}&status=success");
            exit;
        } else {
            header("Location: settings.php?uid={$id}&status=pfperror");
            exit;
        }
    } else {
        header("Location: settings.php?uid={$id}&status=captchafail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bio'])) {
    $bioText = htmlentities($_POST['bio']);
    if (strlen($bioText) > 50) {
        header("Location: settings.php?uid={$id}&status=toolong");
        exit();
    }
    $sql = "UPDATE users SET bio = :biotxt WHERE users_id = :id"; 
    $stmt = $dbh->prepare($sql);
    $stmt->execute(['biotxt' => $bioText, 'id' => $id]);
    header("Location: settings.php?uid={$id}");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['background'])) {
    $hcaptcha_secret = 'ES_ffa43c35f6b14412ade25f0be97e4fec';
    $response = $_POST['h-captcha-response'];
    $verify_url = 'https://hcaptcha.com/siteverify';
    $data = array(
        'secret' => $hcaptcha_secret,
        'response' => $response
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($verify_url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        $target_dir = "uploads/backgrounds/";
        $target_file = $target_dir . basename($_FILES["background"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES["background"]["size"] > 8000000) { // 8MB
            header("Location: settings.php?uid={$id}&status=backgroundsize");
            exit;
        }

        $check = getimagesize($_FILES["background"]["tmp_name"]);
        if ($check === false) {
            header("Location: settings.php?uid={$id}&status=backgroundfile");
            exit;
        }

        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_extensions)) {
            header("Location: settings.php?uid={$id}&status=backgroundext");
            exit;
        }

        $unique_filename = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        if (move_uploaded_file($_FILES["background"]["tmp_name"], $target_file)) {
            $user_id = strip_tags($user['users_id']);
            $sql = "UPDATE users SET profilebkg = :background WHERE users_id = :user_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':background', $target_file);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $profilebkgstatus = "True\n";
            header("Location: settings.php?uid={$id}&status=success");
            exit;
        } else {
            header("Location: settings.php?uid={$id}&status=backgrounderror");
            exit;
        }
    } else {
        header("Location: settings.php?uid={$id}&status=captchafail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_username'])) {
    $newUsername = trim($_POST['new_username']);
    $newUsername = htmlspecialchars($newUsername, ENT_QUOTES, 'UTF-8');

    if (strpos($newUsername, ' ') === false) {
        if (strlen($newUsername) > 0 && strlen($newUsername) <= 15) {
            $stmt = $dbh->prepare("SELECT * FROM users WHERE users_uid = :username");
            $stmt->execute(['username' => $newUsername]);
            $existingUser = $stmt->fetch();
            
            if (!$existingUser) {
                $sql = "UPDATE users SET users_uid = :username WHERE users_id = :id";
                $stmt = $dbh->prepare($sql);
                $stmt->execute(['username' => $newUsername, 'id' => $id]);
                header("Location: settings.php?uid={$id}&status=usernamechanged");
                exit();
            } else {
                header("Location: settings.php?uid={$id}&status=usernameerror");
                exit();
            }
        } else {
            header("Location: settings.php?uid={$id}&status=invalidusername");
            exit();
        }
    } else {
        header("Location: settings.php?uid={$id}&status=usernamecontainspaces");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_hexcode'])) {
    $newHexcode = $_POST['new_hexcode'];
    
    if (preg_match('/^#[a-fA-F0-9]{6}$/', $newHexcode)) {
        $sql = "UPDATE users SET hex_code = :colour WHERE users_id = :id";
        $stmt = $dbh->prepare($sql);
        $success = $stmt->execute(['colour' => $newHexcode, 'id' => $id]);
        if ($success) {
            header("Location: settings.php?uid={$id}&status=colourchanged");
            exit();
        } else {
            header("Location: settings.php?uid={$id}&status=". $stmt->errorInfo()[2]);
            exit();
        }
    } else {
        header("Location: settings.php?uid={$id}&status=invalidcolour");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['current_password']) && isset($_POST['new_password'])) {
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

        
        header("Location: settings.php?uid={$id}&status=passwordchanged");
        exit();
    } else {
        
        header("Location: settings.php?uid={$id}&status=passworderror");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SkidBin - Settings</title>
    <?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div>
      <h2 class="text-center" style="color: white;">Editing <b><?php echo strip_tags($user['users_uid']); ?></b></h2>
      <img class="img-center" style="margin-bottom:12px" src="<?php echo strip_tags($user['profileimg']) ?>" width="120px" height="120px">
      <div class='reglog-form'>
        <form action="/includes/pfp.inc.php?uid=<?php echo strip_tags($user['users_id']) ?>" method="post" enctype="multipart/form-data">
            <label for="img">Profile Picture (Max: 2MB)</label>
            <br>
            <input type="file" name="img" id="img">
            <div class="h-captcha" data-sitekey="ca809027-c3cb-42b6-9dd8-31d626eaa472"></div>
            <input type="submit" name="submit" value="Change profile picture">
            <?php
                if ($_GET["status"] == "success") {
                    echo '<p style="color: green;">Profile picture set.</p>';
                }
                else if ($_GET["status"] == "pfpsize") {
                    echo '<p style="color: red;">Profile picture must be under 2MB.</p>';
                }
                else if ($_GET["status"] == "pfperror") {
                    echo '<p style="color: red;">There was an error uploading your profile picture.</p>';
                }
                else if ($_GET["status"] == "pfpext") {
                    echo '<p style="color: red;">File must be a .jpg, .jpeg,. .gif or .png</p>';
                }
                else if ($_GET["status"] == "pfpfile") {
                    echo '<p style="color: red;">File isn\'t encoded like an image.</p>';
                }
                if ($_GET["status"] == "captchafail") {
                    echo '<p style="color: red;">Captcha not solved.</p>';
                }
            ?>

            </form>
        </div>
        
        <hr width="500px">
        
      
        <div class='reglog-form'>
            <form action="/settings.php?uid=<?php echo $id; ?>" method="post">
                <label for="bio">Bio (Max: 50 characters)</label>
                <br>
                <input type="text" name="bio" value="<?php echo htmlspecialchars(strip_tags($user['bio'])); ?>">
                <input type="submit" name="submit" value="Edit bio">
                <?php
                   
                    if (isset($_GET["status"]) && $_GET["status"] == "toolong") {
                        echo '<p style="color: red;">Bio must be under 50 characters.</p>';
                    }
                ?>
            </form>
        </div>

        <hr width="500px">

        <div class='reglog-form'>
            <form action="/settings.php?uid=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                <label for="background">Background (Size suggestion: 1920x1080)</label>
                <br>
                <input type="file" name="background" id="background" accept="image/*" required>
                <div class="h-captcha" data-sitekey="ca809027-c3cb-42b6-9dd8-31d626eaa472"></div>
                <input type="submit" name="submit" value="Change profile background">
                <?php
                if ($_GET["status"] == "success") {
                    echo '<p style="color: green;">Profile background set.</p>';
                }
                else if ($_GET["status"] == "backgroundsize") {
                    echo '<p style="color: red;">Profile background must be under 10MB.</p>';
                }
                else if ($_GET["status"] == "backgrounderror") {
                    echo '<p style="color: red;">There was an error uploading your profile background.</p>';
                }
                else if ($_GET["status"] == "backgroundext") {
                    echo '<p style="color: red;">File must be a .jpg, .jpeg,. .gif or .png</p>';
                }
                else if ($_GET["status"] == "backgroundfile") {
                    echo '<p style="color: red;">File isn\'t encoded like an image.</p>';
                }
                if ($_GET["status"] == "captchafail") {
                    echo '<p style="color: red;">Captcha not solved.</p>';
                }
                ?>
            </form>
        </div>
        
        <hr width="500px">

        <div class='reglog-form'>
            <form action="/settings.php?uid=<?php echo $id; ?>" method="post">
                <label for="new_username">New Username</label>
                <br>
                <input type="text" name="new_username" required>
                <br><br>
                <input type="submit" name="submit" value="Change Username">
                <?php
                    if (isset($_GET["status"])) {
                        if ($_GET["status"] == "usernamechanged") {
                            echo '<p style="color: green;">Username changed successfully.</p>';
                        } elseif ($_GET["status"] == "usernameerror") {
                            echo '<p style="color: red;">Username already taken!</p>';
                        } elseif ($_GET["status"] == "invalidusername") {
                            echo '<p style="color: red;">Invalid username. Must be 1-15 characters long.</p>';
                        }
                    }
                ?>
            </form>
        </div>
        
        <hr width="500px">
        
        <div class='reglog-form'>
            <form action="/settings.php?uid=<?php echo $id; ?>" method="post">
                <label for="current_password">Current Password</label>
                <br>
                <input type="password" name="current_password" required>
                <br><br>
                <label for="new_password">New Password</label>
                <br>
                <input type="password" name="new_password" required>
                <br><br>
                <input type="submit" name="submit" value="Change Password">
                <?php
                
                    if (isset($_GET["status"])) {
                        if ($_GET["status"] == "passwordchanged") {
                            echo '<p style="color: green;">Password changed successfully.</p>';
                        } elseif ($_GET["status"] == "passworderror") {
                            echo '<p style="color: red;">Incorrect current password.</p>';
                        }
                    }
                ?>
            </form>
        </div>

        <hr width="500px">

        <?php
        if (strip_tags($user['users_rank']) <= 8) {
            echo '<div class=\'reglog-form\'>';
            echo '    <form action="/settings.php?uid='.$_GET['uid'].'" method="post">';
            echo '    <label for="new_hexcode">New Colour Hex:</label>';
            echo '        <br>';
            echo '        <input type="text" name="new_hexcode" required>';
            echo '        <br><br>';
            echo '        <input type="submit" name="submit" value="Change hex code">';

                if (isset($_GET["status"])) {
                    if ($_GET["status"] == "colourchanged") {
                        echo '<p style="color: green;">Colour changed successfully.</p>';
                    } elseif ($_GET["status"] == "invalidcolour") {
                        echo '<p style="color: red;">Invalid syntax for hex code.</p>';
                    }
                }
            echo '    </form>';
            echo '</div>';
        }
?>        
    </div>
</body>
</html>
