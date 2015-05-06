<?php
require_once( __DIR__ . '/../vendor/autoload.php');
 Braintree_Configuration::environment('sandbox');
 Braintree_Configuration::merchantId('gqqf7fnhskv66d5j');
 Braintree_Configuration::publicKey('kn5hhtrsfrr2qs6x');
 Braintree_Configuration::privateKey('4d729f285a30366ff092a78284b2adc0');

// Setup the Merchant Account for the Different Currencies
define("BRAINTREE_THB", "gateway_for_thb");
define("BRAINTREE_HKD", "gateway_for_hkd");
define("BRAINTREE_SGD", "gateway_for_sgd");
?>
