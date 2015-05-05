<?php
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/HQlibrary.php'; // Library to handle inputs and controller functionality.

$HQ        = new HQLibrary();
$paypal    = new PayPalPayment();
$braintree = new BrainTreePayment();

$HQ->parseInput($_POST);
$type      = $HQ->getCardType();
$currency  = $HQ->getCurrency();
$cvvLength = $HQ->getCVV();

unset($HQ); // Only used to bootstrap some values in. paypal and braintree extend this class.


if ($type == "visa" || $type == "mastercard") {
    $currCheck = array(
        "usd",
        "eur",
        "aud"
    ); // If currency is usd,eur or aud, then use paypal. 
    if (strlen($cvvLength) == 3) {
        if (in_array($currency, $currCheck)) {
            $paypal->parseInput($_POST);
            $paypal->processPaypal();
        } else {
            $braintree->parseInput($_POST);
            $braintree->processBraintree();
        }
    } else {
        print "<span class='info'>" . ucfirst($type) . " Cards have a 3 digit CVV. Please check</span>";
    }
}

if ($type == "amex") {
    $paypal->parseInput($_POST);
    // check 4 digit ccv
    if (strlen($cvvLength) < 4) {
        print "<span class='info'>Amex Cards have a 4 digit CVV. Please check</span>";
    } elseif ($currency != 'usd') {
        print "<span class='info'>AMEX is only available for USD transactions</span>";
    } else {
        $paypal->processPaypal();
    }
}

?>
