<?php
session_start();
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
?>
<html>
	<head>
		<title>Alis PX Fusion Testing</title>
		<style type="text/css">
			body {font-family: Arial, Verdana, Serif; font-size: .9em; margin: 1em;}
			h1 {font-family: Verdana;}
		</style>
	</head>
	<body>
		<h1>Alis PX Fusion Testing</h1>

		<?php if (! $transaction_id) : ?>
			<p>Enter transaction id (copied from previous step) and click Query to determine transaction outcome.</p>
			<form action="" method="POST">
				<input type="hidden" name="session_id" value="<?php echo $session_id; ?>" />
				<table>
					<tr>
						<td>Transaction ID</td>
						<td><input type="text" name="transaction_id" value="<?php echo $session_id; ?>" /></td>
						<td><small>(usually youd fetch this from database based on session id returned in URL by DPS</small></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" name="query" value="query" /></td>
						<td></td>
					</tr>
				</table>
			</form>
		<?php else : ?>
			<table>
				<?php foreach ($transaction_details as $key => $value) : ?>
				<tr>
					<td><?php echo $key; ?></td>
					<td><?php echo $value; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</body>
</html>