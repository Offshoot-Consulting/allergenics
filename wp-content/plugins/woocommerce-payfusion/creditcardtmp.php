<?php

if($_POST['ali_payfusion-card-number']==''){
		throw new Exception( __( 'Please Provide Credit Card Number', 'ali_payfusion' ) );
	} else{
			  $cc_number=$_POST['ali_payfusion-card-number'];
			  /* Validate; return value is card type if valid. */
			   $false = false;
			   $card_type = "";
			   $card_regexes = array(
				  "/^4\d{12}(\d\d\d){0,1}$/" => "visa",
				  "/^5[12345]\d{14}$/"       => "mastercard",
				  "/^3[47]\d{13}$/"          => "amex",
				  "/^6011\d{12}$/"           => "discover",
				  "/^30[012345]\d{11}$/"     => "diners",
				  "/^3[68]\d{12}$/"          => "diners",
			   );
			 //mail("syedaliahmad@gmail.com","cccheck 1 ","user=".$pxuser." pwd=".$pxpwd);
			   foreach ($card_regexes as $regex => $type) {
				   if (preg_match($regex, $cc_number)) {
					   $card_type = $type;
					   break;
				   }
			   }
			  //mail("syedaliahmad@gmail.com","cccheck 2 ","user=".$pxuser." pwd=".$pxpwd);
			   if (!$card_type) {
				   throw new Exception( __( 'Invalid Credit Card Number', 'ali_payfusion' ) );
			   }
			 
			   /*  mod 10 checksum algorithm  */
			   $revcode = strrev($cc_number);
			   $checksum = 0; 
			 
			   for ($i = 0; $i < strlen($revcode); $i++) {
				   $current_num = intval($revcode[$i]);  
				   if($i & 1) {  /* Odd  position */
					  $current_num *= 2;
				   }
				   /* Split digits and add. */
					   $checksum += $current_num % 10; if
				   ($current_num >  9) {
					   $checksum += 1;
				   }
	   }
	  //mail("syedaliahmad@gmail.com","cccheck 3","user=".$pxuser." pwd=".$pxpwd);
	   if ($checksum % 10 == 0) {
		   //return $card_type;
	   } else {
		   throw new Exception( __( 'Invalid Credit Card Number', 'ali_payfusion' ) );
	   }
	
	
	}
	if($_POST['ali_payfusion-card-cvc']==''){
		throw new Exception( __( 'Please enter card security code', 'ali_payfusion' ) );
	}				   
	/*if($_POST['ali_payfusion-card-holder']==''){
		throw new Exception( __( 'Please enter card holder name', 'ali_payfusion' ) );
	}*/				   
	if($_POST['ali_payfusion-card-expiry']==''){
		throw new Exception( __( 'Please enter card Expiry date', 'ali_payfusion' ) );
	}else{
		$expdate1=explode("/",$_POST['ali_payfusion-card-expiry']);
		if (intval($expdate1[0])>12 || intval($expdate1[0])==0){
			throw new Exception( __( 'Month must be between 01 and 12', 'ali_payfusion' ) );
		}
	}
	
	

?>