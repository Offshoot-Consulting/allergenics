<?php
session_start();
include "PxPay_Curl.inc.php";

  $PxPay_Url    = "https://sec.paymentexpress.com/pxaccess/pxpay.aspx";
  $PxPay_Userid = $_SESSION['a2aid']; #Important! Update with your UserId
  $PxPay_Key    =  $_SESSION['a2akey']; #Important! Update with your Key
  #
  # MAIN
  #

  $pxpay = new PxPay_Curl( $PxPay_Url, $PxPay_Userid, $PxPay_Key );
  
   if (isset($_REQUEST["result"]))
  {
    # this is a redirection from the payments page.
    print_result();
  }
  
  function print_result()
{
  global $pxpay;

  $enc_hex = $_REQUEST["result"];
  #getResponse method in PxPay object returns PxPayResponse object
  #which encapsulates all the response data
  $rsp = $pxpay->getResponse($enc_hex);


  # the following are the fields available in the PxPayResponse object
  $Success           = $rsp->getSuccess();   # =1 when request succeeds
  $AmountSettlement  = $rsp->getAmountSettlement();
  $AuthCode          = $rsp->getAuthCode();  # from bank
  $CardName          = $rsp->getCardName();  # e.g. "Visa"
  $CardNumber        = $rsp->getCardNumber(); # Truncated card number
  $DateExpiry        = $rsp->getDateExpiry(); # in mmyy format
  $DpsBillingId      = $rsp->getDpsBillingId();
  $BillingId    	 = $rsp->getBillingId();
  $CardHolderName    = $rsp->getCardHolderName();
  $DpsTxnRef	     = $rsp->getDpsTxnRef();
  $TxnType           = $rsp->getTxnType();
  $TxnData1          = $rsp->getTxnData1();
  $TxnData2          = $rsp->getTxnData2();
  $TxnData3          = $rsp->getTxnData3();
  $CurrencySettlement= $rsp->getCurrencySettlement();
  $ClientInfo        = $rsp->getClientInfo(); # The IP address of the user who submitted the transaction
  $TxnId             = $rsp->getTxnId();
  $CurrencyInput     = $rsp->getCurrencyInput();
  $EmailAddress      = $rsp->getEmailAddress();
  $MerchantReference = $rsp->getMerchantReference();
  $ResponseText		 = $rsp->getResponseText();
  $TxnMac            = $rsp->getTxnMac(); # An indication as to the uniqueness of a card used in relation to others

  if ($rsp->getSuccess() == "1")
  {
    $result = "The transaction was approved.";
	
		# Sending invoices/updating order status within database etc.
		header('Location: '.$_SESSION['succurl'].'?statuscode='.$Success.'&statustext='.$ResponseText.'&txnid='.$TxnId.'&txnref='.$_SESSION['txnref']); //success url
	
	if (!isProcessed($TxnId))
	{
		# Send emails, generate invoices, update order status etc.
	}
	
  }
  else
  {
    $result = "The transaction was declined.";
    header('Location: '.$_SESSION['failurl'].'?statuscode='.$Success.'&statustext='.$ResponseText.'&txnid='.$TxnId.'&txnref='.$_SESSION['txnref']); //failure url
  }
exit;
  print <<<HTMLEOF
<html>
<head>
<title>Direct Payment Solutions PxPay transaction result</title>
</head>
<body>
<h1>Direct Payment Solutions PxPay transaction result</h1>
<p>$result</p>
  <table border=1>
	<tr><th>Name</th>				<th>Value</th> </tr>
	<tr><td>Success</td>			<td>$Success</td></tr>
	<tr><td>TxnType</td>			<td>$TxnType</td></tr>
	<tr><td>CurrencyInput</td>		<td>$CurrencyInput</td></tr>
	<tr><td>MerchantReference</td>	<td>$MerchantReference</td></tr>
	<tr><td>TxnData1</td>			<td>$TxnData1</td></tr>
	<tr><td>TxnData2</td>			<td>$TxnData2</td></tr>
	<tr><td>TxnData3</td>			<td>$TxnData3</td></tr>
	<tr><td>AuthCode</td>			<td>$AuthCode</td></tr>
	<tr><td>CardName</td>			<td>$CardName</td></tr>
	<tr><td>CardHolderName</td>		<td>$CardHolderName</td></tr>
	<tr><td>CardNumber</td>			<td>$CardNumber</td></tr>
	<tr><td>DateExpiry</td>			<td>$DateExpiry</td></tr>
	<tr><td>CardHolderName</td>		<td>$CardHolderName</td></tr>
	<tr><td>ClientInfo</td>			<td>$ClientInfo</td></tr>
	<tr><td>TxnId</td>				<td>$TxnId</td></tr>
	<tr><td>EmailAddress</td>		<td>$EmailAddress</td></tr>
	<tr><td>DpsTxnRef</td>			<td>$DpsTxnRef</td></tr>
	<tr><td>BillingId</td>			<td>$BillingId</td></tr>
	<tr><td>DpsBillingId</td>		<td>$DpsBillingId</td></tr>
	<tr><td>AmountSettlement</td>	<td>$AmountSettlement</td></tr>
	<tr><td>CurrencySettlement</td>	<td>$CurrencySettlement</td></tr>
	<tr><td>TxnMac</td>				<td>$TxnMac</td></tr>
	<tr><td>ResponseText</td>		<td>$ResponseText</td></tr>
</table>
</body>
</html>
HTMLEOF;
}
  
/*  
  
  
  
// Determine whether to show the default page or get a transaction started
$transaction_id = $_SESSION['txnid'];
$session_id = isset($_GET['sessionid']) ? $_GET['sessionid'] : false;

if ( ! $transaction_id AND ! $session_id)
{
	die('Nothing to do...');
}

if ($transaction_id)
{
	// Make sure you have entered your Px Fusion credentials in PxFusion.php
	require_once 'PxFusion.php';

	$pxf = new PxFusion($_SESSION['userid'],$_SESSION['pwd']); # handles most of the Px Fusion magic
	
	$response = $pxf->get_transaction($transaction_id);
	$transaction_details = get_object_vars($response->GetTransactionResult);
	
	if ($transaction_details['responseCode']=='00'){
		header('Location: '.$_SESSION['succurl'].'?statuscode='.$transaction_details['responseCode'].'&statustext='.$transaction_details['responseText'].'&txnid='.$transaction_details['transactionId'].'&txnref='.$_SESSION['txnref']); //success url
	}else{
		header('Location: '.$_SESSION['failurl'].'?statuscode='.$transaction_details['responseCode'].'&statustext='.$transaction_details['responseText'].'&txnid='.$transaction_details['transactionId'].'&txnref='.$_SESSION['txnref']); //failure url
	
	}
}
*/
?>