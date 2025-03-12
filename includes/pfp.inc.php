<?php

session_start();

include '../classes/dbh.php';
include '../html/ban_check.php';

if (isset($_GET['uid'])) {
    if (isset($_SESSION['useruid'])) {
        $id = strip_tags($_GET['uid']);
        $stmt = $dbh->prepare("SELECT * FROM users WHERE users_id=:id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (empty(strip_tags($user['users_uid']))) { // messy way of doing it, but it works :)
            header("Location: ../error.php?status=User not found");
            die();
        }

        if ($_SESSION["useruid"] == strip_tags($user['users_uid']) || $_SESSION["rank"] == 1 || $_SESSION['rank'] == 4) { // I want to kill myself
            if(isset($_POST["submit"])) {
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
                    $img=$_FILES['img']; 
                    $filename = $img['tmp_name'];
                    $filerealname = $img['name'];
                    $client_id='67fd839d20ce847';		// stole this from some guys github LOLOL
                    $timeout = 30;       

                    $handle = fopen($filename, 'r');
                    $data = fread($handle, filesize($filename));
                    $pvars = array('image' => base64_encode($data));

                    $fileSize = $img['size'];
                    $fileType = $img['type'];
                    $fileError = $img['error'];

                    $fileExt = explode('.', $filerealname);
                    $fileActualExt = strtolower(end($fileExt));

                    $allowed = array('jpg', 'jpeg', 'gif', 'png');

                    if (in_array($fileActualExt, $allowed)) {
                        if ($fileError === 0) { 
                            if ($fileSize < 2000000) {
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
                                curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
                                curl_setopt($curl, CURLOPT_POST, 1);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
                            
                                $out = curl_exec($curl);
                                curl_close ($curl);
                                $pms = json_decode($out,true);
                                $url=$pms['data']['link'];

                                $sql = "UPDATE users SET profileimg = :pfpimg WHERE users_id=:id"; 
                                $result = $dbh->prepare($sql);
                                    $values = array(':pfpimg'           => $url,
                                                    ':id'               => strip_tags($user['users_id'])                        
                                    );
                                    $res = $result->execute($values);
                                    header('Location: ../settings.php?uid='.strip_tags($user['users_id']).'');
                                }
                            else {
                                header("Location: ../settings.php?status=pfpsize");
                            }
                        }
                        else {
                            header("Location: ../settings.php?status=pfperror");
                        }
                    }
                    else {
                        header("Location: ../settings.php?status=pfpext");
                    }
                }
                else {
                    header("Location: ../settings.php?status=captchafail");
                }
            }
        }
        else {
            header("Location: ../error.php?status=Prohibited");
        }
    }
    else {
        echo '<p>fuck off</p>';
    }
}
else {
    header("Location: ../error.php?status=Missing parameter");
    die();
}