<?php
  session_start();

  include 'classes/dbh.php';
  include 'html/ban_check.php';

  function xss($data){
    return htmlspecialchars(htmlentities($data));
  }

  if (!$_SESSION["rank"] == 1 || !$_SESSION['rank'] == 4 || !$_SESSION['rank'] == 5 || !$_SESSION['rank'] == 6) {
    header("Location: /error.php?status=Prohibited");
    die();
  }
?>

<?php
    $stmt7 = $dbh->prepare("SELECT * FROM comments");
    $stmt7->execute();
    $fff7 = $stmt7->fetchAll();
    $countt7 = $stmt7->rowCount();

   /* $stmt = $dbh->prepare("SELECT * FROM comments ORDER BY `com_time` DESC LIMIT 100");
    $sql = 'SELECT * FROM comments ORDER BY `com_time` DESC LIMIT :calc,:nrop';
    $stmt->execute();
    $fff = $stmt->fetchAll();
    $countt = $stmt->rowCount();*/

    $page 	 = isset( $_GET['page'] ) ? (int) $_GET['page'] : 1;
    $perPage = isset( $_GET['per-page'] ) && $_GET['per-page'] <= 50 ? (int) $_GET['per-page'] : 50;

    // positioning
    $start = ( $page > 1 ) ? ( $page * $perPage ) - $perPage : 0;

    // query
    $articles = $dbh->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM comments ORDER BY `com_time` DESC LIMIT {$start}, {$perPage}" );
    $articles->execute();
    $articles = $articles->fetchAll( PDO::FETCH_ASSOC );

    // pages
    $total = $dbh->query( "SELECT FOUND_ROWS() as total" )->fetch()['total'];
    $pages = ceil( $total / $perPage );

    $articles2 = $dbh->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM doxes WHERE private=0 ORDER BY `add` DESC LIMIT {$start}, {$perPage}" );
    $articles2->execute();
    $articles2 = $articles2->fetchAll( PDO::FETCH_ASSOC );

    // pages
    $total2 = $dbh->query( "SELECT FOUND_ROWS() as total" )->fetch()['total'];
    $pages2 = ceil( $total2 / $perPage );


    /*if (intval($_GET['page']) > $pages) { Niggers.
        header("Location: modcp.php?action=comments");
    }

    if (intval($_GET['page']) > $pages2) {
        header("Location: modcp.php?action=pastes");
    }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Mod CP</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="text-center">
        <h1>Mod CP</h1><br>
        <h3><a href="/modcp.php?action=comments">Manage Comments</a><h3>
        <h3><a href="/modcp.php?action=pastes">Manage Pastes</a><h3>
        <h3><a href="/modcp.php?action=flags">Manage Flags</a><h3>
    </div>
    <?php
        if (strip_tags($_GET['action']) == "comments") {
            if (!isset($_GET['page'])) {
                header("Location: modcp.php?action=comments&page=1");
            }
            echo '
            <div class="container">
            <h1>Comments</h1>
            <p>There are '.intval($total).' comments.</p>
            <a href="/modcp.php?action=comments&page='.$page + 1 .'">Next page ('.$page + 1 .')</a><br>';
            if (intval($_GET['page']) != 1 || !isset($_GET['page'])) {
                echo '
                <a style="padding-bottom: 10px;" href="/modcp.php?action=comments&page='.$page - 1 .'">Previous page ('.$page - 1 .')</a>';
            }
            echo '
            <table class="table table-hover">
            <thead class="tb-highlight">
            <br>
                <tr>
                <th>Comment</th>
                <th>Author</th>
                <th>Posted</th>
                <th>Paste</th>
                <th>Options</th>
                </tr>
            </thead>
            <tbody>
            ';
            foreach($articles as $e) {
                $pid = strip_tags($e['com_paste']);
                $stmtP = $dbh->prepare("SELECT * FROM doxes WHERE id = :pid");
                $stmtP->execute(['pid' => strip_tags($pid)]);
                $userP = $stmtP->fetch();

                $uuid = strip_tags($e['com_author']);
                $stmtU = $dbh->prepare("SELECT users_id FROM users WHERE users_uid = :uuid");
                $stmtU->execute(['uuid' => strip_tags($uuid)]);
                $userU = $stmtU->fetch();

                if (!empty(strip_tags($userP['id']))) { // messy way of doing it, but it works :)
                    echo '
                    <tr class="tb-highlight" id="'.strip_tags($e['com_id']).'">
                    <td><p>'.strip_tags($e['com_text']).'</p></td>
                    <td><a class="paste-link" href="/profile.php?uid='.strip_tags($userU['users_id']).'">'.strip_tags($e['com_author']).'</a></td>
                    <td>'.strip_tags($e['com_time']).'</td>
                    <td><a class="paste-link" href="/viewpaste.php?id='.strip_tags($e['com_paste']).'">'.strip_tags($userP['title']).'</a></td>
                    <td><a class="paste-link" href="/includes/delcom.inc.php?cid='.strip_tags($e['com_id']).'">Delete</a></td>
                    <tr>
                    ';
                }
            }
            echo '
            </tbody>
            </table>
            </div>
            ';
        }

        if (strip_tags($_GET['action']) == "pastes") {
            if (!isset($_GET['page'])) {
                header("Location: modcp.php?action=pastes&page=1");
            }
            echo '
            <div class="container">
            <h1>Pastes</h1>
            <p>There are '.intval($total2).' pastes.</p>
            <a href="/modcp.php?action=pastes&page='.$page + 1 .'">Next page ('.$page + 1 .')</a><br>';
            if (intval($_GET['page']) != 1 || !isset($_GET['page'])) {
                echo '
                <a style="padding-bottom: 10px;" href="/modcp.php?action=pastes&page='.$page - 1 .'">Previous page ('.$page - 1 .')</a>';
            }
            echo '
            <table class="table table-hover">
            <thead class="tb-highlight">
            <br>
                <th>Title</th>
                <th>Made by</th>
                <th>Date created</th>
                <th>Options</th>
            </thead>
            <tbody>
            ';
            foreach($articles2 as $eS) {
                $_SESSION['usr'] = $eS['username'];
                echo '
                <tr class="tb-highlight" id="'.strip_tags($eS['id']).'">
                <td><a class="paste-link" target="_blank" title="'.strip_tags($eS['title']).'" href="/viewpaste.php?id='.strip_tags($eS['id']).'">'.strip_tags($eS['title']).'</a></td>
                <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($eS['uid']).'">'.strip_tags($_SESSION['usr']).'</a></td>
                <td>'.strip_tags($eS['add']).'</td>
                <td><a class="paste-link" href="/delete.php?pid='.strip_tags($eS['id']).'">Delete</a></td>
                </tr>
                ';
            }
            echo '
            </tbody>
            </table>
            </div>
            ';
        }

        if (strip_tags($_GET['action']) == "flags") {
            $stmt = $dbh->prepare("SELECT * FROM flagged_pastes");
            $stmt->execute();
            $flagged_pastes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            echo '<div class="container">';
            echo '<h1>Flagged Pastes</h1>';
            echo '<p>There are ' . count($flagged_pastes) . ' flagged pastes.</p>';
            echo '<table class="table table-hover">';
            echo '<thead class="tb-highlight">';
            echo '<tr>';
            echo '<th>Title</th>';
            echo '<th>Creator</th>';
            echo '<th>Reason</th>';
            echo '<th>Options</th>';
            echo '<th>Options</th>';
            echo '<th>Options</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($flagged_pastes as $paste) {
                
                echo '<tr>';
                echo '<td>' . xss($paste['name']) . '</td>';
                echo '<td>' . xss($paste['creator_id']) . '</td>';
                echo '<td>' . xss($paste['reason']) . '</td>';
                echo '<td><a href="/readflag.php?pid=' . $paste['id'] . '">Read paste</a></td>';
                echo '<td><a href="/restore.php?pid=' . $paste['id'] . '">Restore</a></td>';
                echo '<td><a href="modcp.php?action=unflag_paste&paste_id=' . $paste['id'] . '">Unflag</a></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
    ?>
</body>
</html>