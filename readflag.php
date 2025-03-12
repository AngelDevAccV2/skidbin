<?php
    session_start();

    include 'classes/dbh.php';
    include 'html/ban_check.php';

    $allowed_ranks = [1, 6, 4];

    if (!isset($_SESSION["rank"]) || !in_array($_SESSION["rank"], $allowed_ranks)) {
        header("Location: error.php?status=Prohibited");
        exit();
    }

    if (isset($_GET['pid'])) {
        $id = intval($_GET['pid']);

        $stmt = $dbh->prepare("SELECT * FROM flagged_pastes WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $flagged_paste = $stmt->fetch();

        if (!$flagged_paste) {
            header("Location: error.php?status=Paste not flagged");
            exit();
        }

        $allowed_permissions = [1, 2, 4, 5, 6];
        if (!in_array($_SESSION["rank"], $allowed_permissions) || ($_SESSION["useruid"] != $user['username'] && $_SESSION["rank"] != 1 && $_SESSION["rank"] != 4 && $_SESSION["rank"] != 6 && $_SESSION["rank"] != 5)) {
            header("Location: error.php?status=Prohibited");
            exit();
        }
    } else {
        header("Location: error.php?status=Paste ID not provided");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Read Flag Panel</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <div class="bin-buttons">
    </div>
    <div class="bin-text">
        <?php echo $unflag_message; ?>
        
        <?php echo $flagged_paste['data']; ?>
    </div>
</body>
</html>