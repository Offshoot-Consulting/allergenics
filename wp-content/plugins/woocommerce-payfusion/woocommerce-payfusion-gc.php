<?php
/* Payment Express Payment Gateway Class */
class Ali_PayFusion extends WC_Payment_Gateway {

	// Setup our Gateway's id, description and other values
	function __construct() {

		// The global ID for this Payment method
		$this->id = "ali_payfusion";

		// The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
		$this->method_title = __( "Payment Express PayFusion", 'ali_payfusion' );

		// The description for this Payment Gateway, shown on the actual Payment options page on the backend
		$this->method_description = __( "WooCommerce Implementation of the PXfusion Gateway method of Payment Express", 'ali_payfusion' );

		// The title to be used for the vertical tabs that can be ordered top to bottom
		$this->title = __( "Payment Express PayFusion", 'ali_payfusion' );

		// If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
		$this->icon = null;

		// Bool. Can be set to true if you want payment fields to show on the checkout 
		// if doing a direct integration, which we are doing in this case
		$this->has_fields = true;

		// Supports the default credit card form
		$this->supports = array( 'default_credit_card_form' );
		//$this->supports[] = 'default_credit_card_form';	
		//$this->credit_card_form();
		$this->init_form_fields();

		// After init_settings() is called, you can get the settings and load them into variables, e.g:
		// $this->title = $this->get_option( 'title' );
		$this->init_settings();
		
		// Turn these settings into variables we can use
		foreach ( $this->settings as $setting_key => $value ) {
			$this->$setting_key = $value;
		}
		
		// Lets check for SSL
		//add_action( 'admin_notices', array( $this,	'do_ssl_check' ) );
		
		// Save settings
		if ( is_admin() ) {
			// Versions over 2.0
			// Save our administration options. Since we are not going to be doing anything special
			// we have not defined 'process_admin_options' in this class so the method in the parent
			// class will be used instead
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}		
	} // End __construct()

	
	public function init_form_fields() {
		$this->form_fields = array(
		'enabled' => array(
			'title'		=> __( 'Enable / Disable', 'ali_payfusion' ),
			'label'		=> __( 'Enable this payment gateway', 'ali_payfusion' ),
			'type'		=> 'checkbox',
			'default'	=> 'no',
		),
		'title' => array(
			'title'		=> __( 'Title', 'ali_payfusion' ),
			'type'		=> 'text',
			'desc_tip'	=> __( 'Payment title the customer will see during the checkout process.', 'ali_payfusion' ),
			'default'	=> __( 'Credit Card Payment', 'ali_payfusion' ),
		),
		'description' => array(
			'title'		=> __( 'Description', 'ali_payfusion' ),
			'type'		=> 'textarea',
			'desc_tip'	=> __( 'Payment description the customer will see during the checkout process.', 'ali_payfusion' ),
			'default'	=> __( 'Pay securely using your credit card.', 'ali_payfusion' ),
			'css'		=> 'max-width:350px;'
		),
		'api_login' => array(
			'title'		=> __( 'PX Fusion API Login', 'ali_payfusion' ),
			'type'		=> 'text',
			'desc_tip'	=> __( 'This is the API Login provided by Payment Express when you signed up for an account.', 'ali_payfusion' ),
		),
		'api_pwd' => array(
			'title'		=> __( 'PXFusion API Password', 'ali_payfusion' ),
			'type'		=> 'password',
			'desc_tip'	=> __( 'This is the API Password provided by Payment Express when you signed up for an account.', 'ali_payfusion' ),
		),
		'api_login_test' => array(
			'title'		=> __( 'PX Fusion Test API Login', 'ali_payfusion' ),
			'type'		=> 'text',
			'desc_tip'	=> __( 'This is the TEST API Login provided by Payment Express when you signed up for an account.', 'ali_payfusion' ),
		),
		'api_pwd_test' => array(
			'title'		=> __( 'PXFusion TEST API Password', 'ali_payfusion' ),
			'type'		=> 'password',
			'desc_tip'	=> __( 'This is the API Password provided by Payment Express when you signed up for an account.', 'ali_payfusion' ),
		),
		'environment' => array(
			'title'		=> __( 'Payment Express Test Mode', 'ali_payfusion' ),
			'label'		=> __( 'Enable Test Mode', 'ali_payfusion' ),
			'type'		=> 'checkbox',
			'description' => __( 'Place the payment gateway in test mode.', 'ali_payfusion' ),
			'default'	=> 'no',
		)
		);
		
	}
	
	
	public function process_payment( $order_id ) {
	global $woocommerce;
	
	// Get this Order's information so that we know
	// who to charge and how much
	$customer_order = new WC_Order( $order_id );
	
	// Are we testing right now or is it a real transaction
	//$environment = ( $this->environment == "yes" ) ? 'TRUE' : 'FALSE';

	// Decide which URL to post to
	$environment_url = 'https://sec.paymentexpress.com/pxmi3/pxfusionauth';
	if ( $this->environment == "yes" ){
			$pxuser=$this->api_login_test;
			$pxpwd=$this->api_pwd_test;
	}else{
			$pxuser=$this->api_login;
			$pxpwd=$this->api_pwd;
	}
	
	//mail("syedaliahmad@gmail.com","PXfusion credentials ","user=".$pxuser." pwd=".$pxpwd);
	
					   
	require_once 'PxFusion.php';

	$pxf = new PxFusion($pxuser,$pxpwd); # handles most of the Px Fusion magic

	// Work out the probable location of return.php since this sample
	// code could be anywhere on a development server.
	$returnUrl = 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/return.php';

	// Set some transaction details
	$pxf->set_txn_detail('txnType', 'Purchase');	# required
	$pxf->set_txn_detail('currency', 'NZD');		# required
	$pxf->set_txn_detail('returnUrl', $returnUrl);	# required
	$pxf->set_txn_detail('amount', $customer_order->order_total);		# required
	$pxf->set_txn_detail('merchantReference', 'Order#'.$order_id."-".$customer_order->billing_first_name." ".$customer_order->billing_last_name );

	// Some of the many optional settings that could be specified:
	$pxf->set_txn_detail('enableAddBillCard', 0);
	$pxf->set_txn_detail('txnRef', substr(uniqid() . rand(1000,9999), 0, 16)); # random 16 digit reference);

	// Make the request for a transaction id
	$response = $pxf->get_transaction_id();

	if ( ! $response->GetTransactionIdResult->success)
	{
		//die('There was a problem getting a transaction id from DPS');
		throw new Exception( __( 'There was a problem getting a transaction id from DPS', 'ali_payfusion' ) );
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

    					   
	$expdate=explode("/",$_POST['ali_payfusion-card-expiry']);
	//mail("syedaliahmad@gmail.com","woo txn details 0","txn_id=".$transaction_id." SessionId=".$session_id." expiry=".trim($expdate[0]).trim($expdate[1]));
	// This is where the fun stuff begins
	$payload = array(
		//PXFUSION Credentials and API Info
		"SessionId"           	=> $session_id,
		"Action"              	=> 'Add',
		"Object"            	=> "DpsPxPay",
		// Credit Card Information
		"CardNumber"           	=> str_replace( array(' ', '-' ), '', $_POST['ali_payfusion-card-number'] ),
		"Cvc2"               	=> ( isset( $_POST['ali_payfusion-card-cvc'] ) ) ? $_POST['ali_payfusion-card-cvc'] : '',
		"ExpiryMonth"          	=> trim($expdate[0]),
		"ExpiryYear"            => trim($expdate[1]),
		//"CardHolderName"        => $_POST['ali_payfusion-card-holder']
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

	// Send this payload to Payment Express for processing
	/*$response = wp_remote_post( $environment_url, array(
		'method'    => 'POST',
		'body'      => http_build_query( $payload ),
		'timeout'   => 90,
		'sslverify' => false,
	) );*/
	
	//print_r($payload);
	//mail("syedaliahmad@gmail.com","woo txn details date","date=".$payload['ExpiryMonth'].$payload['ExpiryYear']);
	//echo "<br>";
	//echo "<br>";
	$environment_url = 'https://sec.paymentexpress.com/pxmi3/pxfusionauth';
	$ch = curl_init($environment_url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query( $payload ));
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieJar);
	curl_setopt( $ch, CURLOPT_HEADER, 1);
	//curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

	
	$response = curl_exec( $ch );

	//print_r($response);
	
	
	$info =curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	//CURLINFO_EFFECTIVE_URL
	//CURLINFO_REDIRECT_URL
	//echo "<br>";
	//echo "<br>";
	//print_r($info);
	//mail("syedaliahmad@gmail.com","woo txn details","txn_id=".$transaction_id." SessionId=".$session_id);
	if ($transaction_id)
	{
		// Make sure you have entered your Px Fusion credentials in PxFusion.php
		//require_once 'PxFusion.php';

		$pxf2 = new PxFusion($pxuser,$pxpwd); # handles most of the Px Fusion magic
		
		$response = $pxf2->get_transaction($transaction_id);
		$transaction_details = get_object_vars($response->GetTransactionResult);
		

					//foreach ($transaction_details as $key => $value) :
					
						 //echo $key; 
						 //echo $value; 
						 //echo '<BR>';
					
					//endforeach;
	}

	
	
	
	//mail("syedaliahmad@gmail.com","woo txn details 2","txn_id=".$transaction_id." SessionId=".$session_id." txnresptext=".$transaction_details['responseText']);	
	
	
	
	
		
//responseTextAPPROVED

	if ($transaction_details['responseCode']!=00 ) 
		throw new Exception( __( 'Sorry, the card issuer returned an error: '.$transaction_details['responseText'], 'ali_payfusion' ) );

	//if ( empty( $response['body'] ) )
	//	throw new Exception( __( 'Payment Express\'s Response was empty.', 'ali_payfusion' ) );
		
	// Retrieve the body's resopnse if no errors found
	//$response_body = wp_remote_retrieve_body( $response );

	// Parse the response into something we can read
	//foreach ( preg_split( "/\r?\n/", $response_body ) as $line ) {
	//	$resp = explode( "|", $line );
	//}

	// Get the values we need
	$r['response_code']             = $transaction_details['responseCode'];
	//$r['response_sub_code']         = $resp[1];
	//$r['response_reason_code']      = $resp[2];
	$r['response_reason_text']      = $transaction_details['responseText'];

	// Test the code to know if the transaction went through or not.
	
	if (  $r['response_code'] == '00')  {
		// Payment has been successful
		$customer_order->add_order_note( __( 'Payment Express payment completed.', 'ali_payfusion' ) );
											 
		// Mark order as Paid
		$customer_order->payment_complete();

		// Empty the cart (Very important step)
		$woocommerce->cart->empty_cart();
		mail("syedaliahmad@gmail.com","woo txn details status","txn_id=".$transaction_id." SessionId=".$session_id." responseText=".$r['response_reason_text']);
		// Redirect to thank you page
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $customer_order ),
		);
	} else {
		// Transaction was not succesful
		// Add notice to the cart
		wc_add_notice( $r['response_reason_text'], 'error' );
		// Add note to the order for your reference
		$customer_order->add_order_note( 'Error: '. $r['response_reason_text'] );
	}

}

 /*public function credit_card_form( $args = array(), $fields = array() ) {
 
         wp_enqueue_script( 'wc-credit-card-form' );
 
         $default_args = array(
             'fields_have_names' => true, // Some gateways like stripe don't need names as the form is tokenized
         );
 
         $args = wp_parse_args( $args, apply_filters( 'woocommerce_credit_card_form_args', $default_args, $this->id ) );
 
         $default_fields = array(
             'card-number-field' => '<p class="form-row form-row-wide">
                 <label for="' . esc_attr( $this->id ) . '-card-number">' . __( 'Card Number', 'woocommerce' ) . ' <span class="required">*</span></label>
                 <input id="' . esc_attr( $this->id ) . '-card-number" class="input-text wc-credit-card-form-card-number inspectletIgnore" type="text" maxlength="20" autocomplete="off" placeholder="•••• •••• •••• ••••" name="' . ( $args['fields_have_names'] ? $this->id . '-card-number' : '' ) . '" />
             </p>',
             'card-expiry-field' => '<p class="form-row form-row-first">
                 <label for="' . esc_attr( $this->id ) . '-card-expiry">' . __( 'Expiry (MM/YY)', 'woocommerce' ) . ' <span class="required">*</span></label>
                 <input id="' . esc_attr( $this->id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" type="text" autocomplete="off" placeholder="' . __( 'MM / YY', 'woocommerce' ) . '" name="' . ( $args['fields_have_names'] ? $this->id . '-card-expiry' : '' ) . '" />
             </p>',
			 'card-holder-field' => '<p class="form-row form-row-last">
                 <label for="' . esc_attr( $this->id ) . '-card-holder">' . __( 'Holder Name', 'woocommerce' ) . ' <span class="required">*</span></label>
                 <input id="' . esc_attr( $this->id ) . '-card-holder" class="input-text" type="text" autocomplete="off" placeholder="' . __( 'Card Holder Name', 'woocommerce' ) . '" name="' . ( $args['fields_have_names'] ? $this->id . '-card-holder' : '' ) . '" />
             </p>',
             'card-cvc-field' => '<p class="form-row form-row-last">
                 <label for="' . esc_attr( $this->id ) . '-card-cvc">' . __( 'Card Code', 'woocommerce' ) . ' <span class="required">*</span></label>
                 <input id="' . esc_attr( $this->id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc inspectletIgnore" type="text" autocomplete="off" placeholder="' . __( 'CVC', 'woocommerce' ) . '" name="' . ( $args['fields_have_names'] ? $this->id . '-card-cvc' : '' ) . '" />
             </p>'
         );
 
         $fields = wp_parse_args( $fields, apply_filters( 'woocommerce_credit_card_form_fields', $default_fields, $this->id ) );
         ?>
         <fieldset id="<?php echo $this->id; ?>-cc-form">
             <?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
             <?php
                 foreach ( $fields as $field ) {
                     echo $field;
                 }
             ?>
             <?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
             <div class="clear"></div>
         </fieldset>
         <?php
     }*/
	
	
 
	
	
	
	
	
}
?>
