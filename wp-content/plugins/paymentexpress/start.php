<?php
session_start();

if ($_POST){
	if ($_POST['failure_submit']=='Go back to Payment Page'){
	$_SESSION['sessid']='';
	$_SESSION['txnid']='';
	//$_SESSION['a2aid']=$fld_user_name_val2;
	//$_SESSION['a2akey']=$fld_pwd_val2;
	
	//$english_format_number = number_format($entry[59]);
	//$_SESSION['amnt']=  number_format($entry[59], 2, '.', '');
	//$_SESSION['fstname']=$entry['6.3'];
	//$_SESSION['lstname']=$entry['6.6'];
	
 	//$fld_ccform_url_val = get_option( $fld_ccform_url );
	//$fld_succ_url_val = get_option( $fld_succ_url);	
	//$fld_fail_url_val = get_option( $fld_fail_url );
	//$_SESSION['ccformurl']=$fld_ccform_url_val;
	$plugin_dir = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/paymentexpress/';
	//echo $plugin_dir;
	require_once $plugin_dir.'PxFusion.php';
	
	$pxf = new PxFusion($_SESSION['userid'],$_SESSION['pwd']);
	

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
        	
	
        $_SESSION['txnref']=substr(uniqid() . rand(1000,9999), 0, 16);
	$pxf->set_txn_detail('txnRef',$_SESSION['txnref'] ); // random 16 digit reference);
        // mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 2', $fld_user_name_val.$fld_pwd_val);	

	// Make the request for a transaction id
	$response = $pxf->get_transaction_id();
	//print_r($response);
         //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 3', print_r($response));	

	if ( ! $response->GetTransactionIdResult->success)
	{
		$message = print_r($response, true);
		mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field txn failure',$message);
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
		
		
		$_SESSION['paytype']="FUSION";
		//mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 4', $_SESSION['fstname'].$_SESSION['lstname']);	

		//must be redirected from gravity form notification
		header('Location: ccform.php');
		
	}
	// We've got everything we need to generate 
	}
}else{
	echo "invalid access technique";
}	






?>