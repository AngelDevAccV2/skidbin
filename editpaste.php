<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  if ($_POST) {
  // everything below here is super messy so i wouldn't be surprised if there's some sort of vuln.

    if (!$_SESSION["rank"] == 1 || !$_SESSION["rank"] == 2 || !$_SESSION["rank"] == 4) {
        header("Location: error.php?status=Prohibited");
        die();
    }

    if (isset($_GET['pid'])){
        $id = $_GET['pid'];
        $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
        $stmt->execute(['id' => strip_tags($id)]);
        $user = $stmt->fetch();
        $contents = htmlentities($_POST['pasteContents']);
        if (empty($_POST['pasteContents'])) {
            header("Location: /error.php?status=Paste is empty");
            die();
        }
        if ($_SESSION["rank"] == 4 || $_SESSION["rank"] == 1) {
            if ($_SESSION["useruid"] == strip_tags($user['username']) || $_SESSION["rank"] == 1 || !$_SESSION["rank"] == 4) {
                    $paste = fopen("pastes/".strip_tags($user['title']).".txt", "w");
                    chmod("pastes/".strip_tags($user['title']), 0644); // marks paste as rw-r--r--
                    header("Location: viewpaste.php?id=".strip_tags($user['id']));
                    fwrite($paste, $contents);
                    fclose($paste);
            }
            else {
                header("Location: error.php?status=Prohibited");
                die();
            }
        }
    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Edit Paste</title>
<?php include 'html/head.html' ?>
</head>
<body class="past">
    <form action="" method="post">
        <div class="bin-buttons">
            <input type="submit" name="submit" value="Submit Paste" >
        </div>
    <div class="bin-text">
        <?php
            $id = $_GET['pid'];
            $stmt = $dbh->prepare("SELECT * FROM doxes WHERE id=:id");
            $stmt->execute(['id' => strip_tags($id)]);
            $user = $stmt->fetch();

            if(!intval($_GET['pid'])) {
                header("Location: error.php?status=Paste not found");
                die();
            }

            if (empty(strip_tags($user['id']))) {
                header("Location: error.php?status=Paste not found");
                die();
            }

            if ($_SESSION["useruid"] == strip_tags($user['username']) || $_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
                error_reporting(0);
                $uu = $user['title'];
                if(!file_exists("pastes/".$uu.".txt")) {
                    header("Location: error.php?status=Paste not found");
                    die();
                } // haxor
                echo '<textarea class="bin-text" name="pasteContents" id="pasteContent">';
                include("pastes/".$uu.".txt");
                echo '</textarea>';
                }
                else {
                header("Location: error.php?status=Prohibited");
                die();
            }
        ?>
    </div>
    </form>
</body>
</html>