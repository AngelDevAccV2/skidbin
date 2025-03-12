<?php
  session_start();
  include 'classes/dbh.php';
  include 'html/ban_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>SkidBin - Prices</title>
<?php include 'html/head.html' ?>
</head>
<body>
    <?php include 'html/header.php' ?>
    <h1 class="text-center">SKIDBIN</h1>
    <div class="container">
    <h4 class="text-center">Do you want your paste pinned? If so, then you've came to the right place. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Duration</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>7 days</td>
                <td>12$</td>
            </tr>
            <tr class="tb-highlight">
                <td>14 days</td>
                <td>23$</td>
            </tr>
            <tr class="tb-highlight">
                <td>30 days</td>
                <td>28$</td>
            </tr>
        </tbody>
    </table>
    </div>
    
    <div class="container">
    <h4 class="text-center">Has you're information been uploaded on skidbin and you want it taken down? If so you have come to the right palce. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Methods</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>1 Paste Removal</td>
                <td>5$</td>
            </tr>
            <tr class="tb-highlight">
                <td>All Paste Removal</td>
                <td>30$</td>
            </tr>
            <tr class="tb-highlight">
                <td>Blacklist For All Information</td>
                <td>90$</td>
            </tr>
        </tbody>
    </table>
    </div>
    
       <div class="container">
    <h4 class="text-center">Wall Of Clown Prices. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Methods</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>3 Months Wall Of Clown</td>
                <td>40$</td>
            </tr>
            <tr class="tb-highlight">
                <td>6 Months Wall Of Clown</td>
                <td>90$</td>
            </tr>
            <tr class="tb-highlight">
                <td>Lifetime Wall Of Clown</td>
                <td>800$</td>
            </tr>
        </tbody>
    
    </table>
    </div>
       <div class="container">
    <h4 class="text-center">Usernames. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Methods</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>One Number Usernames</td>
                <td>40$</td>
            </tr>
            <tr class="tb-highlight">
                <td> One Letter Usernames</td>
                <td>50$</td>
            </tr>
            <tr class="tb-highlight">
                <td>Letter Username 1 and A</td>
                <td>$100 Each (RAREST NAMES)</td>
            </tr>
        </tbody>
    </table>
    </div>
    </table>
    </div>
       <div class="container">
    <h4 class="text-center">Custom backgrounds. </h4>
    <h5 class="text-center">After making your decision please contact us via any of the methods <a href="/contact.php">here</a>.</h5><br>
    <table class="table">
        <thead style="background-color: #121212;">
        <tr>
            <th>Methods</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
            <tr class="tb-highlight">
                <td>Custom Background</td>
                <td>40$</td>
            </tr>
        </tbody>
    </table>
    </div>

</body>
</html>