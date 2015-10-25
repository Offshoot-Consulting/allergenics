<?php

function mysite_woocommerce_order_status_completed( $order_id ) {
$IsUrgent = 'false';
    $order = new WC_Order( $order_id );
    
    $myuser_id = (int)$order->user_id;
    $user_info = get_userdata($myuser_id);
    
    $items = $order->get_items();
	$products = array();
    foreach ($items as $item) {
        
    	$products[] = $item['product_id'];
    	
    }
	$products = array_unique($products);
	$del_val = 576;
	if(($key = array_search($del_val, $products)) !== false) {
    unset($products[$key]);
	}
	$products = array_values($products);
	$urjent_prd = 574;
	if(in_array($urjent_prd,$products)) {
		
		$IsUrgent = 'true';
	}
	if(($key = array_search($urjent_prd, $products)) !== false) {
    unset($products[$key]);
	}
	$products = array_values($products);
	$prd_array = array('566' => 1,'570' => 2,'568' => 3,'572' => 4);
	foreach($products as $product) {
		$product_id =  $prd_array[$product];
		$first_name = $order->billing_first_name;
		$last_name = $order->billing_last_name;
		$phone = $order->billing_phone;
		$email = $order->billing_email;
		$postcode = $order->billing_postcode;
		$suburb = $order->billing_city;
		$city = $order->billing_state;
		$address_line = $order->billing_address_1 . ', ' . $order->billing_address_2;
		$dateofhairsample = date('Y-m-d'); //[current timestamp (should be this format '2015-04-05' . 'T00:00:00')]
		$dateofbirth  = date('Y-m-d h:i:s',time()); //[get from custom order field (should be this format '2015-04-05' . 'T00:00:00')]
		
		include('api/new_soap_submission.php');
  //  die('here');	
	}
}


function add_test_into_backend( $order_id ) {
$IsUrgent = false;
    $order = new WC_Order( $order_id );
    $data_submit = get_post_meta($order_id,'data_submit',true);
    $data_submit = '';
    if($data_submit != 'Y') {
    $myuser_id = (int)$order->user_id;
    $user_info = get_userdata($myuser_id);
    
    $items = $order->get_items();
  $products = array();
    foreach ($items as $item) {
        
      $products[] = $item['product_id'];
      
    }
  $products = array_unique($products);
  $del_val = 576;
  if(($key = array_search($del_val, $products)) !== false) {
    unset($products[$key]);
  }
  $products = array_values($products);
  $urjent_prd = 574;
  if(in_array($urjent_prd,$products)) {
    
    $IsUrgent = true;
  }
  if(($key = array_search($urjent_prd, $products)) !== false) {
    unset($products[$key]);
  }
  $products = array_values($products);
  $prd_array = array('566' => 1,'570' => 2,'568' => 3,'572' => 4);
  $Countries = new  WC_Countries( $order->billing_country );
    $state = $Countries->states[$order->billing_country][$order->billing_state];
  foreach($products as $product) {
    $product_id =  $prd_array[$product];
    $first_name = $order->billing_first_name;
    $last_name = $order->billing_last_name;
    $phone = $order->billing_phone;
    $email = $order->billing_email;
    $postcode = $order->billing_postcode;
    $suburb = $order->billing_city;
    $city = $state;
    $address_line = $order->billing_address_1 . ', ' . $order->billing_address_2;
    $dateofhairsample = date('Y-m-d'); //[current timestamp (should be this format '2015-04-05' . 'T00:00:00')]
    $dateofbirth  = date('Y-m-d h:i:s',time()); //[get from custom order field (should be this format '2015-04-05' . 'T00:00:00')]
    
    include('api/new_soap_submission.php');
   
  }
  update_post_meta($order_id,'data_submit','Y');
}
}

?>