<?php

session_start();

include '../classes/dbh.php';
include '../html/ban_check.php';

if (isset($_SESSION['useruid'])) { 
    /*$stmt = $dbh->prepare("SELECT FROM payment_log WHERE User_ID = :uuid");
    $stmt->execute(['uuid' => strip_tags($_SESSION['userid'])]);
    $user = $stmt->fetch();
    
    if (!empty($))*/

    if ($_SESSION['rank'] == 2) {
        header("Location: ../index.php");
        die();
    }

    $orID = rand(0, 99999);
    $price = 10;
    $coin = "BTC";
    $desc = "SkidBin rich upgrade";
    $key = "nowpayments_api_key";

    /*if (strip_tags(isset($_GET['paymentid']))) {
        $stmt = $dbh->prepare("SELECT * FROM payment_log WHERE Payment_ID = :pmid");
        $stmt->execute(['pmid' => strip_tags($_GET['paymentid'])]);
        $user = $stmt->fetch();
    }*/

    function makePayment($price,$coin, $orID, $desc, $key) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
            "price_amount": '.$price.',
            "price_currency": "usd",
            "pay_currency": "'.$coin.'",
            "ipn_callback_url": "https://nowpayments.io",
            "order_id": "'.$orID.'",
            "order_description": "'.$desc.'"
        }',
            CURLOPT_HTTPHEADER => array(
            'x-api-key: '.$key.'',
            'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $output = json_decode($response);
        return $output;
    }

    $paymentinfo = makePayment(10, 'BTC', $orID, 'Skidbin Rich Upgrade', 'nowpayments_api_key');

    $address = $paymentinfo->pay_address;
    $amountopay = $paymentinfo->pay_amount;
    $amountusd = $paymentinfo->price_amount;
    $paystatus = $paymentinfo->payment_status;
    $payid = $paymentinfo->order_id;
    $buyid = $paymentinfo->payment_id;
    
//    checkPayment($output->payment_id, $key);
    function checkPayment($buyid, $key){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.nowpayments.io/v1/payment/'.$buyid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
            'x-api-key: '.$key
            ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $output = json_decode($response);
        return $output;
    }

    if (strip_tags(!isset($_GET['paymentid']))) {
        $sql = "INSERT INTO payment_log (Payment_ID, Order_ID, Amount_BTC, User_ID, Description, Status) VALUES (:pmid, :ooid, :priceBTC, :uuid, :odesc, :ostatus)"; 
        $result = $dbh->prepare($sql);
            $values = array(':pmid'             => $buyid,
                            ':ooid'             => $payid,
                            ':priceBTC'         => $amountopay,
                            ':uuid'             => $_SESSION['userid'],
                            ':odesc'            => $desc,
                            ':ostatus'          => $paystatus
                            );
            $res = $result->execute($values);
    }
    
    //makePayment(10, 'BTC', $orID, 'FlameBin Devious Upgrade', 'nowpayments_api_key');

    //checkPayment($output->payment_id, 'nowpayments_api_key');
}

else {
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>FlameBin - Upgrade</title>
<?php include '../html/head.html' ?>
</head>
<body>
    <?php include '../html/header.php' ?>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading" style="background-color: #0D0D0D;">
                <b class="text-center">Purchasing Rich upgrade for 10$</b>
            </div>
            <div class="panel-body" style="display: block;">
            <?php
                if (strip_tags(isset($_GET['paymentid']))) {
                    $payment = checkPayment(strip_tags($_GET['paymentid']), $key);
                    $current_status = $payment->payment_status;
                    $txid = $payment->payin_hash;

                    if ($current_status == "waiting") {
                        echo '<p>Waiting for payment.</p>';
                        echo '<i style="color: white;">Refresh this page every minute or two.</i>';
                    }

                    if ($current_status == "confirming") {
                        echo '<p>Payment recieved. It may take up to 30 minutes for your transaction to confirm.</p>';
                        echo '<a href="https://mempool.space/tx/'.$txid.'" target="_blank">View Transaction</a>';
                    }

                    if ($current_status == "sending" || $current_status == "finished") {
                        // apply rank here
                        $stmt = $dbh->prepare("UPDATE users SET users_rank = 2 WHERE users_id = :usrid");
                        $stmt->execute(['usrid' => strip_tags($_SESSION['userid'])]);
                        $user = $stmt->fetch();

                        $stmt2 = $dbh->prepare("UPDATE payment_log SET `Status` = 'completed' WHERE Payment_ID = :pmid");
                        $stmt2->execute(['pmid' => strip_tags($_GET['paymentid'])]);

                        $_SESSION['rank'] = 2;
                        echo '<p>Thank you for your purchase. <3</p>';
                        echo '<a href="https://mempool.space/tx/'.$txid.'" target="_blank">View Transaction</a>';
                    }
                }

                else { 
                    echo '
                    <p>Send <b>'.$amountopay.' BTC</b> to <b>'.$address.'</b></p></p>
                    <p><b>Payment ID: </b>'.$buyid.'</p>
                    <p><b>Order ID: </b>'.$payid.'</p>
                    <p><a href="/includes/buyrank.inc.php?paymentid='.$buyid.'">Check Status</a></p>
                    <i style="color: white;">Your rank will be automatically applied after 1/2 confirmations on the blockchain.</i><br>
                    <i style="color: white;">This could take up to 30 minutes, if you are having issues please contact <b>@steakdeck</b> or <b>@ExpensiveEscort</b> on Telegram.</i>
                    ';
                }
            ?>
            </div>
            <div class="panel-information">
            </div>
        </div>
    </div>
</body>
</html>