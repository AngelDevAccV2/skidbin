<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
?>

<?php
  $search = filter_input(INPUT_POST, 'search-query', FILTER_SANITIZE_SPECIAL_CHARS);

  $stmt = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=0 AND unlisted=0 ORDER BY `add` DESC LIMIT 100");
  $stmt->execute();
  $fff = $stmt->fetchAll();
  $countt = $stmt->rowCount();

  if (isset($_GET['page']) && strip_tags($_GET['page']) == 2) {
    $stmt = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=0 AND unlisted=0 ORDER BY `add` DESC LIMIT 100,200");
    $stmt->execute();
    $fff = $stmt->fetchAll();
    $countt = $stmt->rowCount();
  }

  $stmt8 = $dbh->prepare("SELECT * FROM doxes WHERE private=0 AND pinned=1 AND unlisted=0 ORDER BY `add` DESC LIMIT 100");
  $stmt8->execute();
  $fff3 = $stmt8->fetchAll();
  $countt3 = $stmt8->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Home</title>
<link rel="icon" href="/uploads/pfp/favicon.png" type="image/x-icon"/>
<?php include 'html/head.html' ?>
</head>
<body>
  <?php
    $tok = md5(rand(0, 12)); // not used (for now)
    $_SESSION['token'] = $tok; 
  ?>
  <?php include 'html/header.php' ?>

  <div class="container">
  <div class="col-lg-12 col-md-12 col-12"><br>
            </b></div></div>
    </div>
  </div>
  <div class="text-center">
  <img style="margin-bottom:12px" src="/uploads/pfp/skidbin.png" width="346px" height="84px">
<h4 style="color: blue;"><b><a href="https://t.me/skidbinsite" rel="norefferer" target="_blank">Official Skidbin Telegram</a></b></h4><br> 
</div>
  <div class="container">
    <div class="div-center">
      <form id="search-form" method="POST" action="">
        <input name="search-query" type="text" placeholder="Search for.." value="<?php echo htmlspecialchars(strip_tags($search));?>">
        <input type="hidden" name="_token" value="<?php echo $tok; ?>">
        <input type="submit" value="Search">
      </form>
    </div>
    <p>Pinned Pastes</p>
    <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>Title</th>
        <th>Made by</th>
        <th>Date created</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    foreach($fff3 as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($e['uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="wealthy-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link wealthy" target="_blank" style="color: #FFFA56;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Wealthy] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 8) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="hacker-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link hacker" target="_blank" style="color: #FF003E;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Hacker] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #7CE181;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #895c95;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Dev] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link mod" target="_blank" style="color: #4AFFF7;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 5) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="manager-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link manager" target="_blank" style="color: #FF4242;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Management] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 6) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="council-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link council" target="_blank" style="color: #444984;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Council] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 7) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="clique-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link clique" target="_blank" style="color: #444871;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[E-Gang] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          if (strip_tags($user5["users_rank"]) == 0){
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      ?>
  </tbody>
  </table>
  <?php 
    $stmt6 = $dbh->prepare("SELECT * FROM doxes WHERE title LIKE CONCAT('%', :tit, '%') AND private = 0");
    $stmt6->execute(['tit' =>  urlencode(strip_tags($search))]);
    $fff2 = $stmt6->fetchAll();
    $countt2 = $stmt6->rowCount();

    if ($_POST) {
      echo '<p>Showing '.intval($countt2) .' result(s) for '.htmlspecialchars(strip_tags($search)).'</p>';
    }
    else {
      echo '<p>Showing 100 (of '.intval($countt) .' total) pastes</p>';
    }
  ?>
  <table class="table table-hover">
    <thead class="tb-highlight">
      <tr>
        <th>Title</th>
        <th>Made by</th>
        <th>Date created</th>
      </tr>
    </thead>
    <tbody>
    <?php
      if ($_POST) {
        foreach($fff2 as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($e['uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="wealthy-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link wealthy" target="_blank" style="color: #FFFA56;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Wealthy] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #7CE181;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 8) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="hacker-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link hacker" target="_blank" style="color: #FF003E;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Hacker] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #895c95;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Dev] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link wealthy" target="_blank" style="color: #88c0d0;font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user['hex_code']) != ''){
            echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link" target="_blank" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
          }
          else {
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($userU['users_uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      }
      else {
        foreach($fff as $e) {
          $_SESSION['usr'] = $e['username'];
          $uid2 = strip_tags($userU['users_uid']);
          $stmt2 = $dbh->prepare("SELECT * FROM users WHERE users_id=:uid2");
          $stmt2->execute(['uid2' => strip_tags($uid2)]);
          $user5 = $stmt2->fetch();

          $uuid = strip_tags($e['uid']);
          $stmtU = $dbh->prepare("SELECT users_uid FROM users WHERE users_id = :uuid");
          $stmtU->execute(['uuid' => strip_tags($uuid)]);
          $userU = $stmtU->fetch();

          if (empty(strip_tags($userU['users_uid']))) {
            $userU['users_uid'] = "Anonymous";
          }

          if (str_contains($e['username'], 'Anonymous')) {
            $_SESSION['usr'] = "Anonymous";
          }
          if (strip_tags($user5["users_rank"]) == 2) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="wealthy-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link wealthy" target="_blank" style="color: #FFFA56;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Wealthy] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 8) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="hacker-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link hacker" target="_blank" style="color: #FF003E;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Hacker] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 1) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="admin-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link admin" target="_blank" style="color: #7CE181;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Admin] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 3) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="asta-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link asta" target="_blank" style="color: #895c95;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Dev] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 4) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link mod" target="_blank" style="color: #4AFFF7;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Mod] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 5) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="mod-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link manager" target="_blank" style="color: #FF4242;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Management] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 6) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="council-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link council" target="_blank" style="color: #444984;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[Council] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user5["users_rank"]) == 7) {
            if (!strip_tags($e['private']) == 1) {
              echo '
              <tr class="clique-post" id="'.strip_tags($e['id']).'">
              <td><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
              <td><a class="paste-link E-Gang" target="_blank" style="color: #444871;font-weight:bold;" href="/profile.php?uid='.strip_tags($e['uid']).'">[E-Gang] '.strip_tags($userU['users_uid']).'</a></td>
              <td>'.strip_tags($e['add']).'</td>
              </tr>
              ';
            }
          }
          else if (strip_tags($user['hex_code']) != ''){
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" style="color: '.strip_tags($user['hex_code']).'" target="_blank" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
          else {
            echo '
            <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
            <td ><a class="paste-link" target="_blank" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
            <td><a class="paste-link" target="_blank" href="/profile.php?uid='.strip_tags($e['uid']).'">'.strip_tags($userU['users_uid']).'</a></td>
            <td>'.strip_tags($e['add']).'</td>
            </tr>
            ';
          }
        }
      }
    ?>
  </tbody>
  </table>
  </div>
</body>
</html>
