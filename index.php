<?php
/*
 * Round One test for HotelQuickly
 * Author: Peter Griffin
 */
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head><title>HQ Round 1 Test </title>
<style>
.errHinting { border: 1px solid red; }
.err { color: red; font-weight:bold; }
.info{ border: 1px solid gray; background: #87ceeb; padding:5px;margin-top:20px;}
td.messages {padding-top:20px;}
</style>
<script src='https://code.jquery.com/jquery-2.1.4.min.js'></script>
<script src="/js/main.js"></script>

</head>
<body>

<form name="hqform" id="hqform" method="POST">
<table>
    <th>Sample Cards</th>
    <tr><td>Visa:</td><td>4009 3488 8888 1881</td></tr>
    <tr><td>Mastercard:</td><td>5435 6850 5100 5777 (PayPal)</td></tr>
    <tr><td>Mastercard:</td><td>5555 5555 5555 4444 (Braintree)</td></tr>
    <tr><td>Amex:</td><td>3411 3411 3411 347</td></tr>
</table>
<table>
<th><h3>Payment Form</h3></th>
<tr><td><strong>Order</strong></td></tr>
<tr><td>Price:</td><td><input name="price" class="price"><br /><span class='priceErr err'></span></td></tr>
<tr><td>Currency:</td>
<td>
 <select class="currency" name="currency">
  <option value="usd">USD</option>
  <option value="eur">EUR</option>
  <option value="thb">THB</option>
  <option value="hkd">HKD</option>
  <option value="sgd">SGD</option>
  <option value="aud">AUD</option>
</select> 
</td></tr>
<tr><td>Customer Name(Full)</td><td><input name="customername" class="customername" id="customername" /><br /><span class='custnameErr err'></span></td></tr>
<tr><td><strong>Payment</strong></td></tr>
<tr><td>Name on Credit Card</td><td><input name="cardname" class="cardname"><br /><span class='cardnameErr err'></span></td></tr>
<tr><td>Card Number</td><td><input name="card1" class="card1 card" maxlength=4 size="4"> - <input name="card2" class="card2 card" maxlength=4 size="4"> - <input name="card3" class="card3 card" maxlength=4 size="4"> - <input name="card4" class="card4 card" maxlength=4 size="4"><br /><span class='cardErr err'></span></td></tr>
<tr><td>Card Expiration</td><td><input name="cardmonth" class="cardmonth" maxlength=2 size="4"> / <input name="cardyear" class="cardyear" maxlength=4 size="4"><label> ( mm/yyyy )</label>
    <br /><span class='cardmonthErr err'></span>
    <br /><span class='cardyearErr err'></span></td></tr>
<tr><td>Card CVV</td><td><input name="cvv" class="cvv" maxlength=4 size="4"><br /><span class='cvvErr err'></span></td></tr>
<tr><td><input class="submit" type="submit" value="Submit"/></td></tr>
<tr><td class="messages" colspan=2></td></tr>
</table>
</form>
</body>
</html>
