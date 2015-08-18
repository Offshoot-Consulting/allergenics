<?php
//Gravity form stuff

add_action("gform_post_submission", "set_post_content", 10, 2);
 function set_post_content($entry, $form){
 //Gravity Forms has validated the data
 //Our Custom Form Submitted via PHP will go here
 // Lets get the IDs of the relevant fields and prepare an email message
 $message = print_r($entry, true);
 // In case any of our lines are larger than 70 characters, we should use wordwrap()
 $message = wordwrap($message, 70);
 //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Fields', $message);
 // Send

	$fld_user_name = 'mt_user_name';
	$fld_pwd = 'mt_pwd';	
	$fld_user_name2 = 'mt_user_name2';
	$fld_pwd2 = 'mt_pwd2';


    	$fld_ccform_url = 'mt_ccform_url';
	$fld_succ_url = 'mt_succ_url';	
	$fld_fail_url = 'mt_fail_url';	
	
	$fld_user_name_val = get_option( $fld_user_name );
 	$fld_pwd_val = get_option( $fld_pwd );
 	$fld_user_name_val2 = get_option( $fld_user_name2 );
 	$fld_pwd_val2 = get_option( $fld_pwd2 );
	$_SESSION['a2aid']=$fld_user_name_val2;
	$_SESSION['a2akey']=$fld_pwd_val2;
	
	//$english_format_number = number_format($entry[59]);
	$_SESSION['amnt']=  number_format($entry[59], 2, '.', '');
	$_SESSION['fstname']=$entry['6.3'];
	$_SESSION['lstname']=$entry['6.6'];
	
 	$fld_ccform_url_val = get_option( $fld_ccform_url );
	$fld_succ_url_val = get_option( $fld_succ_url);	
	$fld_fail_url_val = get_option( $fld_fail_url );
	$_SESSION['ccformurl']=$fld_ccform_url_val;
	$plugin_dir = ABSPATH . 'wp-content/plugins/paymentexpress/';
	require_once $plugin_dir.'PxFusion.php';
	
	$pxf = new PxFusion($fld_user_name_val,$fld_pwd_val);
	
//$returnUrl = 'https://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/return.php';
$http_host   = $_SERVER['HTTP_HOST'];
$server_url  = "https://$http_host";	
//$returnUrl ="https://allergenicstesting.com/wp-content/plugins/paymentexpress/return.php";
$returnUrl =$server_url."/wp-content/plugins/paymentexpress/return.php";
//mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 1',$returnUrl);
	$pxf->set_txn_detail('txnType', 'Purchase');	# required
	$pxf->set_txn_detail('currency', 'NZD');		# required
	$pxf->set_txn_detail('returnUrl', $returnUrl);	# required
	$pxf->set_txn_detail('amount',$_SESSION['amnt']);		# required
        //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 0', $returnUrl);	

	$pxf->set_txn_detail('merchantReference', $_SESSION['fstname'].'_'.$_SESSION['lstname']);
        	
	// Some of the many optional settings that could be specified:
	//$pxf->set_txn_detail('enableAddBillCard', 0);
        $_SESSION['txnref']=substr(uniqid() . rand(1000,9999), 0, 16);
	$pxf->set_txn_detail('txnRef',$_SESSION['txnref'] ); // random 16 digit reference);
        // mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 2', $fld_user_name_val.$fld_pwd_val);	

	// Make the request for a transaction id
	$response = $pxf->get_transaction_id();
	//print_r($response);
         //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 3', print_r($response));	

	if ( ! $response->GetTransactionIdResult->success)
	{
		// mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 3.5', print_r($response));
		die('There was a problem getting a transaction id from DPS');
	}
	else
	{
		// You should store these values in a database
		// ... they are needed to query the transaction's outcome
		$transaction_id = $response->GetTransactionIdResult->transactionId;
		$session_id = $response->GetTransactionIdResult->sessionId;
		$_SESSION['sessid']=$session_id;
		
		$_SESSION['txnid']=$transaction_id;
		$_SESSION['userid']=$fld_user_name_val;
		$_SESSION['pwd']=$fld_pwd_val;
		$_SESSION['succurl']=$fld_succ_url_val;
		$_SESSION['failurl']=$fld_fail_url_val;
		$_SESSION['paytype']="FUSION";
		//mail('syedaliahmad@gmail.com', 'Final txn details', $_SESSION['txnid']." ".$_SESSION['sessid']);	

		//must be redirected from gravity form notification
		
	}
	// We've got everything we need to generate 
 }
 
?>