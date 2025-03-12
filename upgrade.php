<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
  /*if (!isset($_SESSION['useruid'])) {
    header("Location: error.php?status=prohibited");
    die();
  }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Upgrades</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <script src="/assets/js/snow.js"></script> christmas is over >:) -->
<link rel="stylesheet" href="/css/uprgrade.css">
</head>
<body>
  	<?php include 'html/header.php' ?>
  	<h1 class="text-center">Upgrades</h1>
   <h1 class="text-center">Note : These ranks will receive more updates in the future with a estimated 20+ new features in the upcoming months</h1>
  	<div class="text-center" style="color: white; font-size: 18px;">
		<p>Usename preview: <span class="val" style="color: #FFFA56;font-weight:bold;"><span class="rich">[Wealthy] Anonymous</span></span></p>
		<p>Paste highlight color: <span class="val" style="color: #FFFA56;">Gold</span></p>
		<p>More noticeable: Yes</p>
		<p>Private your own pastes: Yes</p>
		<p>Delete your own comments: Yes</p>
		<p>Ability to change your username: Yes</p>
	</div>
	<div class="container text-center">
		<a href="https://t.me/carlyine" type="button" class="btn btn-success">CLICK TO PURCHASE [15$]</a>
	</div>

  	<div class="text-center" style="color: white; font-size: 18px;">
		<p>Usename preview: <span class="val" style="color: #f13333;font-weight:bold;"><span class="rich">[Hacker] Anonymous</span></span></p>
		<p>Paste highlight color: <span class="val" style="color: #f13333;">Red</span></p>
		<p>More noticeable: Yes</p>
		<p>Private your own pastes: Yes</p>
		<p>Delete your own comments: Yes</p>
		<p>Ability to change your username: Yes</p>
	</div>
	<div class="container text-center">
		<a href="https://t.me/carlyine" type="button" class="btn btn-success">CLICK TO PURCHASE [25$]</a>
	</div>
</body>
</html>