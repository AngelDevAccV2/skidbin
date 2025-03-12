<?php
    session_start();

    include 'classes/dbh.php';
    include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Donate</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
      <h1 class="text-center">SkidBin</h1><br>
      <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: orange; font-size: 25px;">Bitcoin (BTC)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">bc1qsvpx7th9em85m5g0fzl28vmx3y2jd3nc8vpt82</b>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: lightblue; font-size: 25px;">Litecoin (LTC)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">LY5H5CRKpWXjKNvMRMiVnsPankYqtqyonU</b>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: white; font-size: 25px;">Ethereum (ETH)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">0x3E3bFd0b6dC2bBd52A5b19A0F34d231e8eF71da6</b>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading text-center" style="background-color: #0D0D0D;">
                <b style="color: #ff6600; font-size: 25px;">Monero (XMR)</b>
            </div>
            <div class="panel-body text-center">
                <b style="color: white;">49RTapZjV7WCs2RMc1Pkkz8BntUGTwkRyThM1vHiLU7JWyEZpBnfQcQ2psc1WkPHVACGrbxhXDw1ySWta3TPdLqGKufccLR</b>
            </div>
        </div>
        <h4 class="text-center">Contact us to be added to the donation leaderboard.</h4>
        <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Donation Amount</th>
            <th>Donated By</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td></td>
                <td><a href="../profile.php?uid=0" rel="noreferrer" target="blank"></a></td>
            </tr>
            </tr>
        </tbody>
    </table>
    </div>
</body>
</html>