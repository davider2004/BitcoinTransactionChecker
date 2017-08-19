<?php
// -- USER INPUTS -- //
$TxID = $_GET['TxID'];
// -- USER INPUTS -- //

// -- CONFIGURATIONS -- //
$link = "http://ramondettidavide.com/software_test/bitcoin_transaction_checker.php"; // REPLACE WITH YOUR PAGE URL
$BlockExplorer = "https://live.blockcypher.com/"; // DO NOT EDIT
$BlockExplorerAPILink = "https://api.blockcypher.com/v1/btc/main/txs/".$TxID."?limit=50&includeHex=true"; // DO NOT EDIT
// -- CONFIGURATIONS -- //

// TRY THIS WITH 62f1085c06aa841bcca8c3ade28c64429c83204a56bf072cd3ed76e22c1495be

$TxLink = "https://live.blockcypher.com/btc/tx/".$TxID;
$ThisLink = $link."?TxID=".$TxID;

if (!$TxID){
    exit("Missing TxID");
}

// Download JSON from BlockCypher
$result = json_decode(file_get_contents($BlockExplorerAPILink),true);

// Check if this is working
if (!$result){
    exit("ERROR! Unable to download informations from block explorer");
}

// Here is the data
$block_hash = $result['block_hash'];
$block_height = $result['block_height'];
$block_index = $result['block_index'];
$hash = $result['hash'];
$addresses = $result['addresses'];
$total = $result['total']; // total amount in satoshis
$fees = $result['fees']; // fees in satoshis
$size = $result['size']; // size in kb
$preference = $result['preference']; // miners preferences
$relayed_by = $result['relayed_by']; // IP address that sends the transaction
$confirmed = $result['confirmed']; // the first confirmation at what time?
$received = $result['received']; // At what the transaction will be received
$ver = $result['ver']; // version
$lock_time = $result['lock_time']; // lock time
$double_spend = $result['double_spend']; // there are any double spends
$vin_sz = $result['vin_sz']; // n
$vout_sz = $result['vout_sz']; // n
$confirmations = $result['confirmations']; // how much confirmations in the transaction
$confidence = $result['confidence']; // confidence
$inputs = $result['inputs'];
$outputs = $result['outputs'];

// Inputs
$prev_hash = $inputs['prev_hash'];
$output_index = $inputs['output_index'];
$script = $inputs['script'];
$output_value = $inputs['output_value'];
$sequence = $inputs['sequence'];
$addresses_inputs = $inputs['addresses'];

// Outputs
$outputs_value = $outputs['value'];
$scripto = $outputs['script'];
$scripto_type = $outputs['script_type'];

// Confirmations test
if ($confirmations > 5 and $confirmations < 30) {
    $safe = "OK";
}elseif ($confirmations == 0) {
    $safe = "NO";
}elseif ($confirmations == 5 or $confirmations == 4) {
    $safe = "ALMOST";
}elseif ($confirmations == 3){
    $safe = "WAIT_MORE";
}elseif ($confirmations == 2 or $confirmations == 1){
    $safe = "NOT_SURE";
}elseif ($confirmations > 30){
    $safe = "OVERNINETHOUSAND_SAFE";
}

// Codes for status
$confirmation_code = array(
    "OK" => "<font color=green>THIS TX HAVE ".$confirmations."! This is safe!</font>",
    "NO" => "<font color=red>WARNING! THIS HAVE 0 CONFIRMATIONS! THIS IS NOT SAFE! WAIT FOR MORE CONFIRMATIONS!</font>",
    "ALMOST" => "<font color=orange>Uhm... this have ".$confirmations." confirmations. Wait for 6 to be 100% sure</font>",
    "WAIT_MORE" => "<font color=orange>YOU HAVE 3 CONFIRMATIONS. WAIT MORE TO BE SURE.",
    "NOT_SURE" => "<font color=red>THIS TX HAVE ".$confirmations." CONFIRMATIONS! NOW THIS IS NOT SAFE AT 100%. Wait for more.",
    "OVERNINETHOUSAND_SAFE" => "<font color=green>THIS TX HAVE ".$confirmations." CONFIRMATIONS! THIS IS 9000% SAFE!"
);

// Realtime option
$realtime = $_GET['realtime'];

if ($realtime == "no" or !$realtime) {
    $realtime_stat = "DISABLED";
}

if ($realtime == "yes") {
    $realtime_stat = "ENABLED";
    header("Refresh: 1; URL=".$ThisLink."&realtime=yes");
}

// Realtime status codes
$realtime_code = array(
    "DISABLED" => "<a href=?TxID=".$TxID."&realtime=yes>Enable</a>/<b><font color=red>Disabled</font></b>",
    "ENABLED" => "<b><font color=green>Enabled (REFRESH TIME: 1s)</a></b>/<a href=?TxID=".$TxID."&realtime=no>Disable</a>" 
);

// Show API
$ShowApiExtract = $_GET['ShowApiExtract'];

if (!$ShowApiExtract == "yes") {
    exit(file_get_contents($BlockExplorerAPILink));
}
?>


<!doctype html>
<html>
<head>
    <title>Transaction Checker</title>

    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style type="text/css">
    body {
        background-color: #f0f0f2;
        margin: 0;
        padding: 0;
        font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
        
    }
    div {
        width: 600px;
        margin: 5em auto;
        padding: 50px;
        background-color: #fff;
        border-radius: 1em;
    }
    a:link, a:visited {
        color: #38488f;
        text-decoration: none;
    }
    @media (max-width: 700px) {
        body {
            background-color: #fff;
        }
        div {
            width: auto;
            margin: 0 auto;
            border-radius: 0;
            padding: 1em;
        }
    }
    </style>    
</head>
<body>
    <div>
        <center>
            <h1>Bitcoin Transaction checker</h1>
            <p><b>TXID: <?php print $TxID; ?></b></p>
            <p><b>Status: <?php print $confirmation_code[$safe]; ?></b></p>
            <p></p>
            <p><b>MORE INFO</b></p>
            <p><b>Block hash:</b> <?php print $block_hash; ?></p>
            <p><b>Block height:</b> <?php print $block_height; ?></p>
            <p><b>Block index:</b> <?php print $block_index; ?></p>
            <p></p>
            <p><b>INPUTS</b></p>
            <p><b>Addresses in input:</b> press on "View on block explorer"</p>
            <p><b>Prev_Hash:</b> <?php print $prev_hash; ?>press on "View on block explorer"</p>
            <p><b>Output index:</b> <?php print $output_index; ?>press on "View on block explorer"</p>
            <p><b>Script:</b> <?php print $script; ?>press on "View on block explorer"</p>
            <p><b>Output value:</b> <?php print $output_value; ?>press on "View on block explorer"</p>
            <p><b>Sequence:</b> <?php print $sequence; ?>press on "View on block explorer"</p>
            <p></p>
            <p><b>OUTPUTS</b></p>
            <p><b>Addresses in output:</b> press on "View on block explorer"</p>
            <p><b>Script:</b> <?php print $scripto; ?>press on "View on block explorer"</p>
            <p><b>Script type:</b> <?php print $scripto_type; ?>press on "View on block explorer"</p>
            <p></p>
            <p><b>Realtime:</b> <?php print $realtime_code[$realtime_stat]; ?></p>
            <p><b><a href="<?php print $ThisLink."?ShowApiExtract=yes"; ?>" target="_blank">See the JSON</a></b></p>
            <p><b><a href="<?php print $BlockExplorerAPILink; ?>" target="_blank">See the JSON from the block explorer APIs</a></b></p>
            <p><b><a href="<?php print $TxLink; ?>" target="_blank">View on block explorer for more informations (BlockCypher LIVE)</a></b></p>
            <p><b><a href="https://github.com/davider2004/BitcoinTransactionChecker?utm_source=orig_software&utm_medium=link&utm_campaign=bitcointransactionchecker&utm_term=bitcointransactionchecker&utm_content=ads1035585" target="_blank">See this on Github!</a></b></p>
        </center>
    </div>
</body>
</html>
