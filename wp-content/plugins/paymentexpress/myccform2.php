<?php

session_start();

# ===========================
# Px Fusion SOAP basic sample
# ===========================

/**
 *	README
 *
 *	Set Px Fusion user id and key/password in PxFusion.php file, copy pxfusion
 *	folder to server and visit index.php
 *
 *	index.php
 *	=========
 *	The default demo page that waits for the user to indicate that they want to
 *	make a payment. This page then gets a transaction id from DPS and generates
 *	payment form using that transaction id. User submits this form which takes them
 *	directly to DPS for transaction processing.
 *
 *	return.php
 *	==========
 *	The page that handles the user's return from DPS.  The user is
 *	unlikely to notice thay they were redirected for payment processing and then
 *	returned.
 *
 *	PxFusion.php
 *	============
 *	A class that handles most of the more complicated interaction with DPS
 *	(set Px Fusion username/password here)
 *
 */

// Determine whether to show the default page or get a transaction started
/*
$start_transaction = isset($_POST['pay']) ? true : false;

if ($start_transaction)
{
	// Make sure you have entered your Px Fusion credentials in PxFusion.php
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
	$pxf->set_txn_detail('merchantReference', 'Px Fusion - PHP');

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
}
*/
?>

<html>
	<head>
		<title>DPS - Px Fusion PHP Sample</title>
		<style type="text/css">
			body {font-family: Arial, Verdana, Serif; font-size: .9em; margin: 1em;}
			h1 {font-family: Verdana;}
		</style>
	</head>
	<body>
		<h1>Px Fusion Sample</h1>
			<div id="form">
			<form enctype='multipart/form-data' action="https://sec.paymentexpress.com/pxmi3/pxfusionauth" method="post">
				<input type="hidden" name="SessionId" value="<?php echo $_SESSION['sessid']; ?>" />
				<input type="hidden" name="Action" value="Add" />
				<input type="hidden" name="Object" value="DpsPxPay" />
				<table>
					<tr>
						<td>Amount</td>
						<td><small style="color: #66cd66;">Set when GetTransactionId was called</small><br/>
						<!--<input type="text" name="Amount" value="12.00" />-->
						</td>
					</tr>
					<tr>
						<td>Card Number</td>
						<td><input type="text" name="CardNumber" value="4111111111111111" maxlength="16" /></td>
					</tr>
					<tr>
						<td>Expiry (mm/yy)</td>
						<td>
							<input type="text" name="ExpiryMonth" value="12" size="2" /> /
							<input type="text" name="ExpiryYear" value="12" size="2" />
						</td>
					</tr>
					<tr>
						<td>Card Security Code</td>
						<td><input type="text" name="Cvc2" value="123" size="4" /></td>
					</tr>
					<tr>
						<td>Card Holder Name</td>
						<td><input type="text" name="CardHolderName" value="Joe Bloggs" /></td>
					</tr>
					<!-- Optional fields for extra data
					<input type="text" name="UserTxnData1" value="" />
					<input type="text" name="UserTxnData1" value="" />
					<input type="text" name="UserTxnData1" value="" />
					-->
					<tr>
						<td></td>
						<td><input type="submit" value="Submit" /></td>
					</tr>
				</table>
				<p>
					<b>Note:</b> For the purposes of this test, copy this transaction id to your clipboard.<br />
					<small>(usually it should be stored in a database to query the transaction outcome later)</small>
				</p>
				<p>
					<input type="text" value="<?php echo $_SESSION['txnid'] ?>" size="32" />
				</p>
				<p>
					<img src="http://www.paymentexpress.com/images/logos_white/paymentexpress.png" alt="Payment Processor" width="276" height="42" />
				</p>
			</form>
			</div>

		
			
	</body>
</html>