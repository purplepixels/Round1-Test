<?php
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('PayPal\\Test', __DIR__);
define("PP_CONFIG_PATH", __DIR__);

require __DIR__ . '/../HQlibrary.php'; // Library to handle inputs and controller functionality.
$HQ = new HQLibrary();
