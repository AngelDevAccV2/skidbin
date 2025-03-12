 <?php
    session_start();

    include 'classes/dbh.php';

    include 'html/ban_check.php';

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    if (isset($_GET['uid'])){
      $username = $_GET['uid'];
      $stmt = $dbh->prepare("SELECT * FROM users WHERE users_uid=:users_uid");
      $stmt->execute(['users_uid' => $username]);
      $user = $stmt->fetch();

      $id = $user['users_id'];

      $stmt = $dbh->prepare("SELECT * FROM doxes WHERE unlisted = 0 ORDER BY `add` DESC LIMIT 100");
      $stmt->execute();
      $fff = $stmt->fetchAll();
      $countt = $stmt->rowCount();
      
      $comments = $dbh->prepare("SELECT * FROM profile_comments WHERE profile_id=:id ORDER BY `created_at` DESC");
      $comments->execute(['id' => strip_tags($id)]);
      $commentRows = $comments->fetchAll();
      $commentCount = $comments->rowCount();

      $stmtUnlis = $dbh->prepare("SELECT * FROM doxes WHERE unlisted = 1 ORDER BY `add` DESC LIMIT 100");
      $stmtUnlis->execute();
      $fffUnlis = $stmtUnlis->fetchAll();
      $counttUnlis = $stmtUnlis->rowCount();

      if (empty(strip_tags($user['users_uid']))) { // messy way of doing it, but it works :)
        header("Location: error.php?status=User not found");
        die();
      }
    }
    else {
        header("Location: error.php?status=Missing parameter.");
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin -  <?php echo strip_tags($user['users_uid']); ?>'s profile</title>
<?php include 'html/head.html' ?>
<?php
  if ($user['users_id'] == 1) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(https://files.catbox.moe/29di7u.jpg);
    }
    </style>';
  }
  if ($user['users_id'] == 8) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(https://files.catbox.moe/c0bl0j.jpg);
    }
    </style>';
  }
    if ($user['users_id'] == 2) {
    echo '
    <style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(https://files.catbox.moe/v02ibs.webp);
    }
    </style>';
  }
  if (strip_tags($user['profilebkg']) != '') {
    echo '<style>
    body {
      background-image: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.65)), url(\'/' . strip_tags($user['profilebkg']) . '\');
    </style>';
  }
?>

<style>
    input[type="submit"]{
        width:40%;
        display:inline-block;
        margin: 15px 10px 15px 0;
    }
    .h-captcha {
        text-align: left;
    }
    .comment i{
        color:#666;
    }
    .comment {
        padding: 10px 0 0 0;
        border-top: solid 1px gray;
        border-bottom: solid 0px gray;
    }
    .comment p{
        margin: 5px 0 10px;
    }
    .comment b a p{
        width:auto;
        display:inline;
    }
    .no-comment{
        text-align: center;
        padding: 80px 0px 60px;
    }
    .no-comment p{
        color:#666
    }
    select {
            color: black;
    }
</style>
</head>
<body>
    <?php include 'html/header.php' ?>
    <div class="">
        <div class="container">
            <div class="col-sm-8">
                  <div class="panel panel-default">
                      <div class="panel-heading" style="background-color: #0D0D0D;">
                      <?php
                            if (strip_tags($user['banned']) == 1) {
                              echo '<p>Profile of <span class="" style="color: #808080 ;font-weight:bold;text-decoration:line-through;">[Banned] '.strip_tags($user['users_uid']).'</span></p>';
                            }
                            else if (strip_tags($user['users_rank']) == 2) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="wealthy" style="color: #FFFA56;font-weight:bold;">[Wealthy] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[E-Gang] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 9) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="hacker" style="color: #FF003E;font-weight:bold;">[Hacker] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[E-Gang] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 1) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="admin" style="color: #7CE181;font-weight:bold;">[Admin] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[Administrator] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 3) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="Dev" style="color: #895c95;font-weight:bold;">[Dev] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[Dev] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 4) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="mod" style="color: #4AFFF7;font-weight:bold;">[Mod] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[Mod] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 5) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="manager" style="color: #FF4242;font-weight:bold;">[Management] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[Management] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 6) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="council" style="color: #444984;font-weight:bold;">[Council] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[Council] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            }
                            else if (strip_tags($user['users_rank']) == 7) {
                              if ((strip_tags($user['hex_code'])) == '') {
                                echo '<p>Profile of <span class="clique" style="color: #444871;font-weight:bold;">[E-Gang] '.strip_tags($user['users_uid']).'</span></p>';
                              } else {
                                echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">[E-Gang] '.strip_tags($user['users_uid']).'</span></p>';
                              }
                            } else if (strip_tags($user['users_rank']) == 8) {
                              echo '<p>Profile of <span class="clique" style="color: '.strip_tags($user['hex_code']).';font-weight:bold;">'.strip_tags($user['users_uid']).'</span></p>';
                            } else {
                              echo '<p>Profile of <span class="" style="font-weight:bold;">'.strip_tags($user['users_uid']).'</span></p>';
                            }
                      ?>
                      </div>
                      <div class="panel-body">
                        <div class="panel-profile">
                          <img style="margin-bottom:12px" src="<?php echo strip_tags($user['profileimg']) ?>" width="120px" height="120px">
                          <?php
                // Display user's bio based on conditions
                if (strip_tags($user['banned']) == 0) {
                    // Check for custom bios based on users_id
                    if ($user['users_id'] == 2) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/rockstar95" target="_blank" style="color: #FF0000; font-weight: bold;">@tongue</a> </p>';
                    } elseif ($user['users_id'] == 1) {
                        echo '<p><b>Account managed by Carolyne & Fees | Contact : </b> <a href="https://t.me/Leyigh" target="_blank" style="color: #39ff14; font-weight: bold;">@anon</a> <a href="https://t.me/cutelarp" target="_blank" style="color: #39ff14; font-weight: bold;">@caroline</a> </p>';
                        } elseif ($user['users_id'] == 51) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/faggs" target="_blank" style="color: #39ff14; font-weight: bold;">@faggs</a>';
                        } elseif ($user['users_id'] == 38) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/egirlpuller54 " target="_blank" style="color: #FFFFFF; font-weight: bold;">@nine</a> - Message For Removals';
                        } elseif ($user['users_id'] == 45) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/ignitifed " target="_blank" style="color: #4AFFF7; font-weight: bold;">@ignite</a>';
                        } elseif ($user['users_id'] == 60) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/coxanax " target="_blank" style="color: #42f566; font-weight: bold;">@conax</a>';
                        } elseif ($user['users_id'] == 107) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/crashouts " target="_blank" style="color: #ff00ff; font-weight: bold;">@Crashouts</a>';
                      } elseif ($user['users_id'] == 528) {
                        echo '<p><b>Telegram:</b> <a href="https://t.me/dickable " target="_blank" style="color: #FFB6C1; font-weight: bold;">@dickable</a>';
                    } else {
                        echo '<p>'.strip_tags($user['bio']).'</p>'; // Display regular bio
                    }
                } else {
                    // Display ban reason and banned by information
                    echo '<p><b>Ban reason:</b> '.strip_tags($user['ban_reason']).'</p>';
                    echo '<p><b>Banned by:</b> '.strip_tags($user['banned_by']).'</p>';
                }
                ?>
                
                        </div>
                      <div class="panel-information">
                        <p><b>User ID: </b><?php echo strip_tags($user['users_id']) ?></p>
                        <p><b>Account created: </b><?php echo strip_tags($user['joined']) ?></p>
                        <p>
                          <?php 
                              if (strip_tags($user['banned']) == 1) {
                                echo "<b>Rank:</b> Banned";
                              } else if (strip_tags($user['users_rank']) == 1) {
                                  echo "<b>Rank:</b> Administrator";
                              }
                              else if (strip_tags($user['users_rank']) == 0) {
                                echo "<b>Rank:</b> User";
                              }
                              else if (strip_tags($user['users_rank']) == 2) {
                                echo "<b>Rank:</b> Wealthy";
                              }
                              else if (strip_tags($user['users_rank']) == 3) {
                                echo "<b>Rank:</b> Dev";
                              }
                              else if (strip_tags($user['users_rank']) == 4) {
                                echo "<b>Rank:</b> Moderator";
                              }
                              else if (strip_tags($user['users_rank']) == 5) {
                                echo "<b>Rank:</b> Management";
                              }
                              else if (strip_tags($user['users_rank']) == 6) {
                                echo "<b>Rank:</b> Council";
                              }
                              else if (strip_tags($user['users_rank']) == 7) {
                                echo "<b>Rank:</b> E-Gang";
                              }
                              
                              if ($_SESSION['rank'] == 4 || $_SESSION["rank"] == 3 || $_SESSION["rank"] == 1) {
                                  echo '<p><b><label for="rank">Select a new rank: </label></b>';
                                  echo '<select name="ranks" id="ranks">';
                                  echo '  <option value="1">Administrator</option>';
                                  echo '  <option value="2">Wealthy</option>';
                                  echo '  <option value="3">Developer</option>';
                                  echo '  <option value="4">Moderator</option>';
                                  echo '  <option value="5">Management</option>';
                                  echo '  <option value="6">Council</option>';
                                  echo '  <option value="7">E-Gang</option>';
                                  echo '  <option value="8">Custom Colour</option>';
                                  echo '  <option value="0">User</option>';
                                  echo '</select></p>';
                                  echo '<input type="submit" name="submit" value="Save" onclick="confirmChange(' . strip_tags($user['users_id']) . ');">';

                                  echo '<script>';
                                  echo 'function confirmChange(user) {';
                                  echo '  if (confirm("Are you sure you want to change this user\'s rank?")) {';
                                  echo '      window.location.href = "/admin/change_rank.php?userid=" + user + "&rank=" + encodeURIComponent(document.getElementById(\'ranks\').value);';
                                  echo '  }';
                                  echo '}';
                                  echo '</script>';
                              } else {
                                echo '';
                              }
                          ?>
                        </p>
                
                        <?php 
                          if ($_SESSION["rank"] == 1) {
                            echo '<p><b>Email:</b> '.strip_tags($user["users_email"]).' (only admins can view this, mods cannot)</p>';
                          }
                          if ($_SESSION["rank"] == 1 || $_SESSION["rank"] == 4) {
                            echo '<p><b></b> <a class="link" href="deleteac.php?uid='.strip_tags($user["users_id"]).'">Ban/unban user</a>';
                          }
                          if (strip_tags($user['users_id']) == $_SESSION['userid'] || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) {
                            echo ' 
                            <p><b></b><a class="link" href="settings.php?uid='.strip_tags($user['users_id']).'">Edit account information</a></p>';
                          }
                      ?>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                      <b><p><?php echo strip_tags($user['users_uid']) ?>'s pastes</p></b>
                      <table class="table table-hover">
                      <thead class="tb-highlight">
                        <tr>
                          <th>Title</th>
                          <th>Date created</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        foreach($fff as $e) {
                          if (strip_tags($user["users_id"]) == strip_tags($e['uid'])) {
                            if (!strip_tags($e['private']) == 1) {
                              echo '
                              <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
                              <td><a class="paste-link" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
                              <td>'.strip_tags($e['add']).'</td>
                              <tr>
                              ';
                            }
                          }
                        }
                      ?>
                      </tbody>
                      </table>
                      <?php
                        if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
                          echo '
                          <b><p>Your private pastes</p></b>
                          <table class="table">
                          <thead class="tb-highlight">
                            <tr>
                              <th>Title</th>
                              <th>Date created</th>
                            </tr>
                          </thead>
                          <tbody>
                          ';
                        }
                        foreach($fff as $e) {
                          if (strip_tags($user["users_id"]) == strip_tags($e['uid']) && $_SESSION["userid"] == strip_tags($e['uid'])) {
                            if (strip_tags($e['private']) == 1) {
                              echo '
                              <tr class="tb-highlight" id="'.strip_tags($e['id']).'">
                              <td><a class="paste-link" title="'.strip_tags($e['title']).'" href="/viewpaste.php?id='.strip_tags($e['id']).'">'.strip_tags($e['title']).'</a></td>
                              <td>'.strip_tags($e['add']).'</td>
                              <tr>
                              ';
                            }
                          }
                        }
                        if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
                          echo '
                          </tbody>
                          </table>
                          ';
                        }
            
                        // UNLISTED
            
                        if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
                          echo '
                          <b><p>Your unlisted pastes</p></b>
                          <table class="table">
                          <thead class="tb-highlight">
                            <tr>
                              <th>Title</th>
                              <th>Date created</th>
                            </tr>
                          </thead>
                          <tbody>
                          ';
                        }
                        foreach($fffUnlis as $eU) {
                          if (strip_tags($user["users_id"]) == strip_tags($eU['uid']) && $_SESSION["userid"] == strip_tags($eU['uid'])) {
                            if (strip_tags($eU['unlisted']) == 1) {
                              echo '
                              <tr class="tb-highlight" id="'.strip_tags($eU['id']).'">
                              <td><a class="paste-link" title="'.strip_tags($eU['title']).'" href="/viewpaste.php?id='.strip_tags($eU['id']).'">'.strip_tags($eU['title']).'</a></td>
                              <td>'.strip_tags($eU['add']).'</td>
                              <tr>
                              ';
                            }
                          }
                        }
                        if (strip_tags($user["users_id"]) == strip_tags($_SESSION['userid'])) {
                          echo '
                          </tbody>
                          </table>
                          ';
                        }
                        
                      ?>
            
                    </div>
                </div>
            </div>
            
            <div class="col-sm-4" style="background-color: #161616;padding:0px">
                <?php
                if (isset($_SESSION['useruid'])) {
                ?>
                <div class="panel-heading" style="background-color: #0D0D0D;">
                    <p style="margin: 5px 0;">Post a Comment</p>                      
                </div>
                <form action="" method="post" style="padding: 0px 15px 20px;">
                    <br>
                    <textarea name="comtextarea" class="comment-text" placeholder="Enter comment" required></textarea>
                    <div class="h-captcha" data-sitekey="ca809027-c3cb-42b6-9dd8-31d626eaa472" data-size="normal"></div>
                    <input type="submit" name="submit" value="Comment">
                    <a href="https://skidbin.site">Back</a>
                </form>
                <?php 
                }
                else{
                ?> 
                <div class="panel-heading" style="background-color: #0D0D0D;">
                    <p style="margin: 5px 0;">Comments</p>                      
                </div>
                <?php  
                }
                ?>
                
                <div  style="padding: 0px 15px 20px;">
                <?php
                if ($commentCount > 0) {
                    foreach ($commentRows as $eC) {
                        $uuid = strip_tags($eC['user_id']);
                        $stmtU = $dbh->prepare("SELECT * FROM users WHERE users_id = :uuid");
                        $stmtU->execute(['uuid' => strip_tags($uuid)]);
                        $userU = $stmtU->fetch();
                ?>
                        <div class="comment">
                            <b>
                                <a target="_blank" href="/profile.php?uid=<?= strip_tags($userU['users_uid']); ?>">
                                    <?php
                                    if (strip_tags($userU['banned']) == 1) {
                                        echo '<p><span class="" style="color: #808080 ;font-weight:bold;text-decoration:line-through;">[Banned] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 2) {
                                        echo '<p><span class="wealthy" style="color: #FFFA56;font-weight:bold;">[Wealthy] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 8) {
                                        echo '<p><span class="hacker" style="color: #FF003E;font-weight:bold;">[Hacker] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 1) {
                                        echo '<p><span class="admin" style="color: #7CE181;font-weight:bold;">[Admin] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 3) {
                                        echo '<p><span class="Dev" style="color: #895c95;font-weight:bold;">[Dev] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 4) {
                                        echo '<p><span class="mod" style="color: #4AFFF7;font-weight:bold;">[Mod] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 5) {
                                        echo '<p><span class="manager" style="color: #FF4242;font-weight:bold;">[Management] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 6) {
                                        echo '<p><span class="council" style="color: #444984;font-weight:bold;">[Council] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else if (strip_tags($userU['users_rank']) == 7) {
                                        echo '<p><span class="clique" style="color: #444871;font-weight:bold;">[E-Gang] ' . strip_tags($userU['users_uid']) . '</span></p>';
                                    } else {
                                        echo '<p><span class="" style="font-weight:bold;">' . strip_tags($userU['users_uid']) . '</span></p>';
                                    }
                                    ?>
                                </a> | <i><?= strip_tags(date("d M, Y - h:i A", strtotime($eC['created_at']))); ?></i></b>
                            <p><?= strip_tags($eC['comment']) ?></p>
                            <?php
                            // Add delete button if condition is true
                            if (strip_tags($user['users_id']) == $_SESSION['userid'] || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) {
                                echo '<a href="#" onclick="confirmDelete(' . strip_tags($eC['id']) . ')" style="color: red; text-decoration: none;">Delete</a>';
                            }
                            // JavaScript function to confirm deletion
                            echo '<script>
                                    function confirmDelete(commentId) {
                                        if (confirm("Are you sure you want to delete this comment?")) {
                                            window.location.href = "/admin/delete_comment.php?comment_id=" + commentId;
                                        }
                                    }
                                  </script>';
                            ?>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="no-comment">
                        <p>No comment found.</p>
                    </div>
                <?php
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST["submit"]) && $_POST["submit"] == "Comment") {
    $data = array (
        'secret' => "ES_ffa43c35f6b14412ade25f0be97e4fec",
        'response' => $_POST['h-captcha-response']
      );
  
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);

    if($responseData->success) {
        $sql = "INSERT INTO profile_comments (`profile_id`, `user_id`, `comment`) VALUES (:profile_id, :user_id, :comment)"; 
        $result = $dbh->prepare($sql);
            $values = array(':profile_id' => strip_tags($_GET['uid']),
                            ':user_id'    => strip_tags($_SESSION['userid']),
                            ':comment'    => htmlentities(test_input($_POST['comtextarea']))
                            );
            $res = $result->execute($values);
        ?>
        <script>
            location.assign("profile.php?uid=<?=strip_tags($_GET['uid']);?>");
        </script>
        <?php
    }
    else {
        header("Location: ../error.php?status=Captcha not solved");
    }
}
?>
