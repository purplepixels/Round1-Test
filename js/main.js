$(document).ready(function() {
 var error = 0;

$(".submit").click(function( event ) {   
  event.preventDefault();
  $('.messages').html('');
  $('.err').html('');
  // Price/Value check
  var price = $('.price').val();
  if(price >= 0 && isNumeric(price)) {
	$('.price').removeClass('errHinting');
      priceVal = parseFloat(price);
      $('.price').val(priceVal.toFixed(2));
  } else if(price < 0 || !isNumeric(price)) {
	$('.price').addClass('errHinting');
    $('.priceErr').html('* Price needs to be numeric and not a negative');
    error++;
  }

var cc_num = $('.card1').val() + $('.card2').val() + $('.card3').val() + $('.card4').val();
 if(!checkLuhn(cc_num) || !cc_num) {
   	error++;
 	$('.card').addClass('errHinting');
    $('.cardErr').html(' * Card Number needs to be complete, all digits, and be a valid Luhn series');
  } else {
  	$('.card').removeClass('errHinting');
  }


  checkCustName = checkExtendedName($('.customername').val());
  checkCardName = checkExtendedName($('.cardname').val());

  if(checkCustName!=true) {
  	$('.customername').addClass('errHinting');
    $('.custnameErr').html('* Customer name needs to be complete, all alphabetic and include first and last name');
  	error++;
 	} else {
 	$('.customername').removeClass('errHinting');
  };

  if(checkCardName!=true) {
  	$('.cardname').addClass('errHinting');
    $('.cardnameErr').html('* Name on Credit Card needs to be complete, all alphabetic and include first and last name');
  	error++;
  } else {
  	$('.cardname').removeClass('errHinting');
  }

cvvCheck($('.cvv').val());
expireCheck($('.cardmonth').val(), $('.cardyear').val());

    if(error > 0) {
  	$('.messages').html('<strong>Please check the items above in red, and then resubmit</strong>');
  } else {

    var data = $("#hqform").serialize();

    $('.messages').html('<strong>Payment Processing. Please hold ... </strong>');
    $.ajax({
        url: "/submit.php",
        type: "POST",
        data: data,
        success: function( data ) { $('.messages').html(data); },
        error: function( data ) { alert( data ); }
    });

    };

// Reset error checking 
error = 0;
});

// Quick check for validity of CC Expiration
function expireCheck(month,year) { 

month=month.replace(/^\s+|\s+$/g,'');
year=year.replace(/^\s+|\s+$/g,'');

  if(isNumeric(month.trim()) && month.toString().length==2 && month > 0 && month <= 12) {
  	$('.cardmonth').removeClass('errHinting'); 
  } else {
 	$('.cardmonth').addClass('errHinting');
    $('.cardmonthErr').html(' * The Card Expiration month is invalid. Please check that it is a number and is between 01 and 12'); 
      error++;
  }
 
  var d = new Date();
  var df = d.getFullYear();  // df = dateFull
  var cardMaxEndDate = parseInt(df) + 15; // Max 15years until expiry of CC (from current Year) 

  if(isNumeric(year.trim()) && year.toString().length==4 && year >= df && year <= cardMaxEndDate) {
        $('.cardyear').removeClass('errHinting');
  } else {
        $('.cardyear').addClass('errHinting');
      $('.cardyearErr').html('* The Card Expiration year is invalid. Please check that it is a number, and is either the current year or is a max of 15 years from now');
      error++;
  }
}

// Quick check for validity of CVV
// Check for 3characters and isNumeric

function cvvCheck(input) {
  if(isNumeric(input.trim()) && input.toString().length<=4 && input.toString().length>=3) {
      
   cardTypeCheck = $('.card1').val().substring(0,1);

   if((cardTypeCheck=="4" || cardTypeCheck=="5") && input.toString().length!=3){
       error++;
       $('.cvv').addClass('errHinting');
        $('.cvvErr').html(' * This card type needs a 3 digit CVV. Please recheck');
   } else if(cardTypeCheck=="3" && input.toString().length!=4) {
       error++;
       $('.cvv').addClass('errHinting');
       $('.cvvErr').html(' * This card type needs a 4 digit CVV. Please recheck');
   } else {
        $('.cvv').removeClass('errHinting');
   }
  
      
  } else {
   	error++;
   	$('.cvv').addClass('errHinting');
    $('.cvvErr').html (' * The CVV is invalid. Please check that it is a number and 3 digits (Visa/Mastercard) or 4 digits (Amex)');
  };

}

// Quick check for overall validity of CC number
function checkLuhn(input)
{
  var sum = 0;
  var numdigits = input.length;
  var parity = numdigits % 2;
  for(var i=0; i < numdigits; i++) {
  	var digit = parseInt(input.charAt(i))
  	if(i % 2 == parity) digit *= 2;
  	if(digit > 9) digit -= 9;
 	sum += digit;
  }
  return (sum % 10) == 0;
}

  // Check Full Names, including spaces and hyphens
  function checkExtendedName(input)
  {
  return /^(([A-Za-z]+[\-\']?)*([A-Za-z]+)?\s)+([A-Za-z]+[\-\']?)*([A-Za-z]+)?$/.test(input);
  }

  // Basic Numeric checker
  function isNumeric(input) {
   	return (input > 0 || input  === 0 || input === '0' || input < 0) && input !== true && isFinite(input);
  }

});

