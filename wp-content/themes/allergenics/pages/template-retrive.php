<?php
/*
Template Name: Retrieve Data
*/
?>
<?php global $wpdb,$woocommerce;
$order_id = trim($_GET['order']);
$order = new WC_Order( $order_id );
$myuser_id = (int)$order->user_id;
$user_info = get_user_by('id',$myuser_id);

$client_first_name = get_user_meta( $myuser_id, 'client_first_name', true ); 
	$client_last_name = get_user_meta( $myuser_id, 'client_last_name', true );
    if($client_last_name != '' && $client_last_name != '') {

    } 
    else {
        $client_first_name = $user_info->first_name;
        $client_last_name = $user_info->last_name;
    }
$info = array($client_first_name,$client_last_name);
echo json_encode($info);
?> 
