<?php

session_start();
if ($_POST){

if ($_POST['input_111']=='choice01'){

	header('Location: PxPay_Redirect.php');

}else if($_POST['input_111']=='choice02'){
             //echo "incorrect";
	    $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
	    $postfields = array("CardNumber" => $_POST['CardNumber'],"ExpiryMonth" => $_POST['ExpiryMonth'],"ExpiryYear" => $_POST['ExpiryYear'],"Cvc2" => $_POST['Cvc2'],"CardHolderName" => $_POST['CardHolderName'],"SessionId" => $_POST['SessionId'],"Action" => $_POST['Action'],"Object" => $_POST['Object']);
	    $url="https://sec.paymentexpress.com/pxmi3/pxfusionauth";
	    $ch = curl_init();
	    $options = array(
	        CURLOPT_URL => $url,
	        CURLOPT_HEADER => true,
	        CURLOPT_POST => 1,
	        CURLOPT_HTTPHEADER => $headers,
	        CURLOPT_POSTFIELDS => $postfields,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_FOLLOWLOCATION => true
	    ); // cURL options
	    curl_setopt_array($ch, $options);
	    curl_exec($ch);
	    if(!curl_errno($ch))
	    {
	        $info = curl_getinfo($ch);
	        //print_r($info);
	        
	        if ($info['http_code'] == 200)
	            $errmsg = "Request sent";
	            header('Location: '.$info['url']);
	    }
	    else
	    {
	        $errmsg = curl_error($ch);
	        echo $errmsg;
	    }
	    curl_close($ch);
	
	

}






}