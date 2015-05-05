<?php
/* Bootstrap Pay Pal & Braintree 
 * Set SSL version = 4 due to POODLE vulnerability
 * Paypal is refusing SSL3 connections 
 */
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
PPHttpConfig::$DEFAULT_CURL_OPTS[CURLOPT_SSLVERSION] = 4;

/* Configure Braintree */
require_once(__DIR__ . '/config/braintree.config.php');
require_once(__DIR__ . '/config/database.settings.php');

class HQLibrary
{
    
    // Setters
    function setPrice($value)
    {
        $this->price = $value;
        return $this;
    }
    
    function setCurrency($value)
    {
        $this->currency = $value;
    }
    
    function setCustName($value)
    {
        $this->custName = $value;
        return $this;
    }
    
    function setCustFirstName($value)
    {
        $this->custFirstName = $value;
        return $this;
    }
    
    function setCustLastName($value)
    {
        $this->custLastName = $value;
        return $this;
    }
    
    
    function setCardName($value)
    {
        $this->cardName = $value;
        return $this;
    }
    
    function setCardMonth($value)
    {
        $this->cardMonth = $value;
        return $this;
    }
    
    function setCardYear($value)
    {
        $this->cardYear = $value;
        return $this;
    }
    
    function setCVV($value)
    {
        $this->CVV = $value;
        return $this;
    }
    
    function setCardNumber($value)
    {
        $this->cardNumber = $value;
        return $this;
    }
    
    function setCardType($value)
    {
        $this->cardType = $value;
        return $this;
    }

    function setOrder($arr)
    {
        $this->Order = $arr;
    }

    
    // Getters 
    function getPrice() {
        if(isset($this->price)) {
        return $this->price;
        } else {
        return null;
        }
    }
    
    function getCurrency()
    {
        if(isset($this->currency)) {
        return $this->currency;
        } else {
        return null;
        }
    }
    
    function getCustName()
    {
        if(isset($this->custName)) {
        return $this->custName;
        } else {
        return null;
        }

    }
    
    function getCardName()
    {
        if(isset($this->cardName)) {
        return $this->cardName;
        } else {
        return null;
        }

    }
    
    function getCardNumber()
    {
        if(isset($this->cardNumber)) {
        return $this->cardNumber;
        } else {
        return null;
        }
    }
    
    function getCardMonth()
    {
        if(isset($this->cardMonth)) {
        return $this->cardMonth;
        } else {
        return null;
        }
    }
    
    function getCardYear()
    {
         if(isset($this->cardYear)) {
        return $this->cardYear;
        } else {
        return null;
        }
    }
    
    function getCVV()
    {
        if(isset($this->CVV)) {
        return $this->CVV;
        } else {
        return null;
        }
    }
    
    function getCustFirstName()
    {
        if(isset($this->custFirstName)) {
        return $this->custFirstName;
        } else {
        return null;
        }
    }
    
    function getCustLastName()
    {
        if(isset($this->custLastName)) {
        return $this->custLastName;
        } else {
        return null;
        }
    }
    
    function getCardType()
    {
        if(isset($this->cardType)) {
        return $this->cardType;
        } else {
        return null;
        }
    }
    
    function getOrder()
    {
        if(isset($this->Order)) {
        return $this->Order;
        } else {
        return null;
        }    }
    
    
    function parseInput(&$arr)
    {
        
        foreach ($arr as $key => $value) {
            switch ($key) {
                case 'price':
                    $this->setPrice($value);
                    break;
                case 'currency':
                    $this->setCurrency($value);
                    break;
                case 'customername':
                    $this->setCustName($value);
                    $custName = explode(" ", $value);
                    $this->setCustFirstName($custName[0]);
                    $this->setCustLastName(end($custName));
                    break;
                case 'cardname':
                    $this->setCardName($value);
                    break;
                case 'card1':
                    $card1 = $value;
                    break;
                case 'card2':
                    $card2 = $value;
                    break;
                case 'card3':
                    $card3 = $value;
                    break;
                case 'card4':
                    $card4 = $value;
                    break;
                case 'cardmonth':
                    $this->setCardMonth($value);
                    break;
                case 'cardyear':
                    $this->setCardYear($value);
                    break;
                case 'cvv':
                    $this->setCVV($value);
                    break;
            }
            
            
            if (!empty($card1) && !empty($card2) && !empty($card3) && !empty($card4)) {
                $cardNumber = $card1 . $card2 . $card3 . $card4;
                $this->setCardNumber($cardNumber);
                $this->determineCardType($cardNumber);
                $this->setOrder($arr); // Store the order Object
            }
        }
        
    }
    
    function determineCardType()
    {
        $number = $this->getCardNumber();
        $number = preg_replace('/[^\d]/', '', $number);
        
        if (preg_match('/^3[47][0-9]{13}$/', $number)) {
            $this->setCardType('amex');
            return 'amex';
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $number)) {
            $this->setCardType('mastercard');
            return 'mastercard';
        } elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
            $this->setCardType('visa');
            return 'visa';
        } else {
            $this->setCardType('Unknown');
            return 'Unknown';
        }
    }
    
    
    function writeToDatabase($orderData, $transactionResult)
    {
        try {
            $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO transactions (orderData, transactionResult) VALUES ('" . serialize($orderData) . "','" . serialize($transactionResult) . "')";
            $conn->exec($sql);
            echo "<br />Transaction saved to Database";
        }
        catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        
        $conn = null;
    }
    
    
    function readDatabaseRecord($recordID)
    {
        try {
            
            $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $sql  = "SELECT * FROM transactions where id=:ID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ID', $recordID, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll();
            if(isset($result[0])) {
                $orderFull = $result[0];
            }

            $orderData       = @unserialize($result[0]['orderData']);
            $transactionData = @unserialize($result[0]['transactionResult']);
            if (isset($orderData) && !empty($orderData)) {
                print "<table>";
                print "<tr><td><strong> Order Number " . $orderFull['id'] . "</strong></td></tr>";
                foreach ($orderData as $key => $value) {
                    print "<tr><td>" . ucfirst($key) . ":<td><td>" . $value . "<td></tr>";
                }
                print "</table>";
                
                print "<table>";
                
                if (is_array($transactionData)) {
                    foreach ($transactionData as $key => $value) {
                        if (!is_array($value)) {
                            print "<tr><td>" . ucfirst($key) . ":<td><td>" . $value . "</td></tr>";
                        } else {
                            foreach ($value as $k => $v)
                                if (!is_array($v)) {
                                    print "<tr><td></td><td>" . ucfirst($k) . ":</td><td>" . $v . "</td></tr>";
                                } else {
                                    foreach ($v as $key2 => $value2) {
                                        if (!is_array($value2)) {
                                            print "<tr><td></td><td>" . ucfirst($key2) . ":</td><td>" . $value2 . "</td></tr>";
                                        }
                                    }
                                }
                        }
                    }
                    print "</table><br /><br />";
                } else {
                    // Parse Braintree
                $brainTree = explode(',', $transactionData);
                    if(is_array($brainTree)) { 
                        foreach ($brainTree as $k => $v) {
                                if (!is_array($v)) {
                                    print "<tr><td></td><td>" . ucfirst($k) . ":</td><td>" . $v . "</td></tr>";
                                } else {
                                    foreach ($v as $key2 => $value2) {
                                        if (!is_array($value2)) {
                                            print "<tr><td></td><td>" . ucfirst($key2) . ":</td><td>" . $value2 . "</td></tr>";
                                        }
                                    }
                                }
                        
                    }
                
                
                }
                     print "</table><br /><br />";
            }
            } else {
             print "Invalid record id";   
            }
        }
        catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
        
        $conn = null;
    }
    
    function readAllDatabaseRecords()
    {
        // get latest record ID
        $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $sql  = "SELECT id FROM transactions ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if(isset($result)&&!empty($result)) {
        $last   = intVal($result[0]['id']);
            if($last!=1) {
                for ($x = 1; $x <= $last; $x++) {
                    $this->readDatabaseRecord($x);
                } 
            } else {
                return $this->readDatabaseRecord('1');
            }
        } else {
            print "No records currently in the Database";
        }
        
    }
    
    
    function error($err)
    {
        print "An error has occurred: " . $err;
        exit(1);
    }
    
}

class PayPalPayment extends HQLibrary
{
    
    function processPaypal()
    {
        
        $cred = null;
        
        $card = new CreditCard();
        $card->setType($this->getCardType());
        $card->setNumber($this->getCardNumber());
        $card->setExpire_month($this->getCardMonth());
        $card->setExpire_year($this->getCardYear());
        $card->setCvv2($this->getCVV());
        $card->setFirst_name($this->getCustFirstName());
        $card->setLast_name($this->getCustLastName());
        
        //funding Instrument. Just a CreditCardfor this HQTest
        $funding = new fundingInstrument();
        $funding->setCredit_card($card);
        
        // Payer
        $payer = new Payer();
        $payer->setPayment_method("credit_card");
        $payer->setFunding_instruments(array(
            $funding
        ));
        
        // Amount
        $amount = new Amount();
        $amount->setCurrency("USD"); //Add to POST and change as necessary!
        $amount->setTotal($this->getPrice());
        
        // Transaction Description
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('PHP coding test for HQ');
        
        // Payment Resource
        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array(
            $transaction
        ));
        
        // Authenticate against API
        $apiContext = new ApiContext($cred, 'Request' . time());
        
        // Process Transaction
        try {
            $payment->create($apiContext);
        }
        catch (\PPConnectionException $ex) {
            
            // Capture known Paypal issue: ref - https://github.com/paypal/PayPal-PHP-SDK/issues/112
            
            $compare = json_decode($ex->getData, true);
            if ($compare = "Exception: Got Http response code 500 when accessing https://api.sandbox.paypal.com/v1/payments/payment. int(1) ") {
                print "Error: Paypal sometimes has issues when the same card number (for example a sample card number), is used a <br> high number of times. Please try another card number.";
            }
            exit(1);
        }
        
        echo $payment->getId();
        //        var_dump($payment->toArray());
        $this->writeToDatabase($this->getOrder(), $payment->toArray());
    }
    
}


class BraintreePayment extends HQLibrary
{
    
    function processBraintree()
    {
        $expireDate = $this->getCardMonth() . "/" . $this->getCardYear();
        $result     = Braintree_Transaction::sale(array(
            'amount' => $this->getPrice(),
            'creditCard' => array(
                'number' => $this->getCardNumber(),
                'expirationDate' => $expireDate
            )
        ));
        
        if ($result->success) {
            print_r("<strong>Payment Successfully Processed</strong><br /> Payment ID: " . $result->transaction->id);
            $this->writeToDatabase($this->getOrder(), $result->transaction);
        } else if ($result->transaction) {
            print_r("Error processing transaction:");
            print_r("\n  code: " . $result->transaction->processorResponseCode);
            print_r("\n  text: " . $result->transaction->processorResponseText);
        } else {
            print_r("Validation errors: \n");
            print_r($result->errors->deepAll());
        }
        
    }
    
}


?>
