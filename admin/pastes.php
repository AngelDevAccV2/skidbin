<?php
  session_start();

  include '../classes/dbh.php';

  if (!$_SESSION["rank"] == 1) {
    header("Location: /error.php?status=Prohibited");
    die();
  }
?>

<?php
  $stmt = $dbh->prepare("SELECT * FROM doxes WHERE private=1 LIMIT 100");
  $stmt->execute();
  $fff = $stmt->fetchAll();
  $countt = $stmt->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Admin Panel</title>
<?php include '../html/head.html' ?>
</head>
<body>
    <?php include '../html/header.php' ?>
    <div class="container">
        <h1>Private Pastes</h1>
        <p>Showing 100 (of <?php echo intval($countt); ?> total) private pastes</p>
        <table class="table">
          <thead class="tb-highlight">
            <tr>
              <th>Title</th>
              <th>Made by</th>
              <th>Date created</th>
              <th>Options</th>
            </tr>
          </thead>
          <tbody>
        <?php
            foreach($fff as $e) {
              if (strip_tags($e['private']) == 1) {
                echo '
                <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
                <td><a class="paste-link" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
                <td><a class="paste-link" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($e['username']).'</a></td>
                <td>'.strip_tags($e['add']).'</td>
                <td><a class="paste-link" href="/delete.php?pid='.strip_tags($e['id']).'">DELETE</a></td>
                <tr>
                ';
              }
            }
          ?>
          </tbody>
        </table>
    </div>
</body>
</html>