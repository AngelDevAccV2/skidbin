<?php
session_start();

include 'classes/dbh.php';
include 'html/ban_check.php';

function unflagPaste($dbh, $id) {
    $stmt = $dbh->prepare("DELETE FROM flagged_pastes WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

function restorePaste($dbh, $id, $name, $creator_id, $data) {
    $stmt = $dbh->prepare("INSERT INTO doxes (id, title, uid, `private`, unlisted, hits, username, pinned, `add`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $name);
    $stmt->bindParam(3, $creator_id);
    $private = 0;
    $unlisted = 0;
    $hits = 0;
    $pinned = 0; // Set the default value for 'pinned'
    $stmt->bindParam(4, $private);
    $stmt->bindParam(5, $unlisted);
    $stmt->bindParam(6, $hits);
    $stmt->bindParam(7, $creator_id);
    $stmt->bindParam(8, $pinned); // Bind the 'pinned' parameter
    $stmt->execute();

    file_put_contents('pastes/' . $name . '.txt', $data);
}

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
    if (!in_array($_SESSION["rank"], $allowed_permissions) || ($_SESSION["useruid"] != $flagged_paste['creator_id'] && $_SESSION["rank"] != 1 && $_SESSION["rank"] != 4 && $_SESSION["rank"] != 6 && $_SESSION["rank"] != 5)) {
        header("Location: error.php?status=Prohibited");
        exit();
    }

    unflagPaste($dbh, $id);

    restorePaste($dbh, $id, $flagged_paste['name'], $flagged_paste['creator_id'], $flagged_paste['data']);

    $unflag_message = '<p>Paste unflagged and restored. ID: ' . $id . '</p>';
    $unflag_message .= '<a href="index.php">Homepage</a>';
} else {
    header("Location: error.php?status=Paste ID not provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Restore Paste Panel</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <div class="bin-buttons">
    </div>
    <div class="bin-text">
        <?php echo $unflag_message; ?> <br>

        <?php echo $flagged_paste['data']; ?>
    </div>
</body>
</html>
