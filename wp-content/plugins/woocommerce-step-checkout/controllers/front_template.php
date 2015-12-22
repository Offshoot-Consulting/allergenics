<?php

/**
 * This is file is responsible for custom logic needed by all templates. NO
 * admin code should be placed in this file.
 */
Class Frontpage {

    /**
     * Run the following methods when this class is loaded
     */
    public function __construct(){
        add_action( 'init', array( &$this, 'init' ) );
		add_filter('woocommerce_login_redirect', array( &$this, 'bryce_wc_login_redirect'));
		add_filter( 'woocommerce_checkout_fields' , array( &$this, 'wcs_checkout_fields'),999 );
		add_action( 'widgets_init', array( &$this,'theme_slug_widgets_init') );
		add_filter( 'woocommerce_add_to_cart_redirect', array( &$this,'custom_add_to_cart_redirect') );
		//add_filters( 'woocommerce_get_cart_url',  array( &$this,'cartUrl'));
		add_filter( 'woocommerce_default_address_fields' , array( &$this, 'wpse_120741_wc_def_state_label') );
		
		
    }

    public function steps() {
    	add_action('wp_head',array( &$this, 'plugin_css' ));
    }

    public function plugin_css() {

    	$output="<style> .header_menu { display : none; } #footer .container { display:none; } .top-header .right-section { display:none; }</style>";

		echo $output;

    }


 
// Change order comments placeholder and label, and set billing phone number to not required.
function wcs_checkout_fields( $fields ) {

unset($fields['billing']['billing_company']);
unset($fields['billing']['billing_first_name']);
unset($fields['billing']['billing_last_name']);
unset($fields['billing']['billing_email']);
unset($fields['billing']['billing_phone']);

return $fields;

}


function wpse_120741_wc_def_state_label( $address_fields ) {

     $address_fields['state']['label'] = 'City';
	// $address_fields['city']['label'] = 'Suburb';
	 //$address_fields['city']['placeholder'] = 'Suburb';
	 $address_fields['address_2']['placeholder'] = '';
	

     return $address_fields;
}

    /**
     * During WordPress' init load various methods.
     */
    public function init(){
		add_filter( 'woocommerce_registration_redirect', array( &$this, 'bryce_wc_register_redirect') );
		//add_action( 'woocommerce_register_post', array( &$this, 'wooc_validate_extra_register_fields', 10, 3) );
		add_filter( 'woocommerce_registration_errors', array( &$this, 'registration_errors_validation') );
		add_action( 'woocommerce_created_customer', array( &$this, 'wooc_save_extra_register_fields') );
		add_action('wp_ajax_wcs_add_to_cart',  array( &$this, 'wcsAddToCart') );
		add_action('wp_ajax_nopriv_wcs_add_to_cart', array( &$this, 'wcsAddToCart') );
		add_action('wp_ajax_wcs_remove_from_cart', array( &$this,'removeToCart') );
		add_action('wp_ajax_nopriv_wcs_remove_from_cart', array( &$this, 'removeToCart'));
		add_action('wp_ajax_wcs_cart',  array( &$this, 'wcsCart') );
		add_action('wp_ajax_nopriv_wcs_cart',  array( &$this, 'removeToCart'));
		add_action('wp_ajax_change_client_info',  array( &$this, 'change_client_info') );
		
		add_action( 'template_redirect', array( &$this, 'wc_custom_redirect_after_purchase' ));
		
		
		
    }
	
	/**
     * During WordPress' init load various methods.
     */
    public function check_step1(){
		
		if ( is_user_logged_in() ) { 
				$redirect = home_url('/step-2');
				wp_redirect($redirect);
				exit;
		
		}
		else {
			add_action( 'woocommerce_register_form_start', array( &$this, 'wooc_extra_register_fields') );
			
			add_action( 'woocommerce_login_form_start', array( &$this, 'wooc_extra_login_fields') );	
		}
		
		
    }
	
/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */
function wooc_extra_login_fields() {
	?>

	<p class="form-row form-row-wide">
	<label for="reg_billing_first_name">New customer?   <a href="javascript:void(0);" id="show_register">Click here to create an account</a></label>
    <input type="hidden" name="for_whome_text" id="for_whome_text" value="0">
	<input type="hidden" name="client_f_name" id="client_f_name" value="<?php if ( ! empty( $_POST['client_f_name'] ) ) esc_attr_e( $_POST['client_f_name'] ); ?>">
	<input type="hidden" name="client_l_name" id="client_l_name" value="<?php if ( ! empty( $_POST['client_l_name'] ) ) esc_attr_e( $_POST['client_l_name'] ); ?>">
	</p>


	<?php
}


	
/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */
function wooc_extra_register_fields() {
	?>

	<p class="form-row form-row-wide">
	<label for="reg_billing_first_name">Already a customer? <a href="javascript:void(0);" id="show_login">Click here</a></label>
	<input type="hidden" name="for_whome_text" id="for_whome_text_reg" value="0">
	<input type="hidden" name="client_f_name" id="client_f_name_reg" value="<?php if ( ! empty( $_POST['client_f_name'] ) ) esc_attr_e( $_POST['client_f_name'] ); ?>">
	<input type="hidden" name="client_l_name" id="client_l_name_reg" value="<?php if ( ! empty( $_POST['client_l_name'] ) ) esc_attr_e( $_POST['client_l_name'] ); ?>">
	</p>

	<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>


	<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>

	

	<!--<p class="form-row form-row-last form-row-phone">
	<label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
	</p>-->
	
	<!--<div class="clear"></div>

	<p class="form-row form-row-wide">
	<label for="reg_billing_address_1"><?php _e( 'Address', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
	</p>-->

	<?php
}



/**
 * Validate the extra register fields.
 *
 * @param  string $username          Current username.
 * @param  string $email             Current email.
 * @param  object $validation_errors WP_Error object.
 *
 * @return void
 */
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}

	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}


	if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
		$validation_errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Phone is required!.', 'woocommerce' ) );
	}
	
	if ( isset( $_POST['for_whome_text'] ) &&  $_POST['for_whome_text'] == 1 ) {
		$validation_errors->add( 'client_first_name', __( '<strong>Error</strong>: Client First Name is required!.', 'woocommerce' ) );
		$validation_errors->add( 'client_last_name', __( '<strong>Error</strong>: Client Last Name is required!.', 'woocommerce' ) );
	}
	return $validation_errors;
}



/**
 * Save the extra register fields.
 *
 * @param  int  $customer_id Current customer ID.
 *
 * @return void
 */
function wooc_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		// WordPress default first name field.
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

		// WooCommerce billing first name.
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}

	if ( isset( $_POST['billing_last_name'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

		// WooCommerce billing last name.
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		
		
	}

	if ( isset( $_POST['billing_phone'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}
	
	if ( isset( $_POST['billing_address_1'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['billing_address_1'] ) );
	}

	if ( isset( $_POST['client_f_name'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $customer_id, 'client_first_name', sanitize_text_field( $_POST['client_f_name'] ) );
	}

	if ( isset( $_POST['client_l_name'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $customer_id, 'client_last_name', sanitize_text_field( $_POST['client_l_name'] ) );
	}
}

/**
 * @param WP_Error $reg_errors
 *
 * @return WP_Error
 */
function registration_errors_validation( $reg_errors ) {

	if ($_POST['for_whome_text'] == 1 ) {

		if ( empty( $_POST['client_f_name'] ) )  {
		$reg_errors->add( 'empty required fields', __( 'Client First Name is required!.', 'woocommerce' ) );
		}
		if ( empty( $_POST['client_l_name'] ))  {
		$reg_errors->add( 'empty required fields', __( 'Client Last Name is required!.', 'woocommerce' ) );
		}
	}

	if ( empty( $_POST['billing_first_name'] ) || empty( $_POST['billing_last_name'] ) || empty( $_POST['billing_phone'] ) )  {
		$reg_errors->add( 'empty required fields', __( 'Please fill in the required fields.', 'woocommerce' ) );
	}
	

	return $reg_errors;
}

	// Custom redirect for users after logging in

function bryce_wc_register_redirect( $redirect ) {
     $redirect = home_url('/step-2');
     return $redirect;
}

// Custom redirect for users after logging in

function bryce_wc_login_redirect( $redirect ) {

	 global $wpdb, $session;
     $redirect = home_url('/step-2');
	 $_SESSION['client_f_name'] = $_POST['client_f_name'];
	 $_SESSION['client_l_name'] = $_POST['client_l_name'];
     return $redirect;
}	

// Custom check for loggedin user
public function checkLogin() {
	global $wpdb, $session;
	
	if ( !is_user_logged_in() ) {
		
		$_SESSION['msg'] = "Please complete step1";
		// One value
		$redirect = home_url('/step-1');
		wp_redirect($redirect);
		exit;
		
	}
	 
}

public function step2() {
	global $session;
	
	$user_ID = get_current_user_id();
	$client_first_name = get_user_meta( $user_ID, 'client_first_name', true ); 
	$client_last_name = get_user_meta( $user_ID, 'client_last_name', true ); 

	if ( isset( $_SESSION['checkout'] ) && $_SESSION['checkout'] == 'Done' ) {
		
		unset($_SESSION['form_completed']);
	}

	if ( isset( $_SESSION['client_f_name'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $user_ID, 'client_first_name', sanitize_text_field( $_SESSION['client_f_name'] ) );
		unset($_SESSION['client_f_name']);
	}

	if ( isset( $_SESSION['client_l_name'] ) ) {
		// WooCommerce billing phone
		update_user_meta( $user_ID, 'client_last_name', sanitize_text_field( $_SESSION['client_l_name'] ) );
		unset($_SESSION['client_l_name']);
	}
	$_SESSION['step2'] = 'visit';
	
}

public function step3() {
	global $session;
	if(isset($_SESSION['step2']) &&  $_SESSION['step2'] == 'visit') {
		
		$_SESSION['step3'] = 'visit';
		if(isset($_GET['form_complete']) && $_GET['form_complete'] == 'true') {
			$_SESSION['form_completed'] = 'true';
		
		}
		
	}
	else {
		$_SESSION['msg'] = "Please complete step2";
		// One value
		$redirect = home_url('/step-2');
		wp_redirect($redirect);
		exit;
	}
	
}


public function step4() {
	global $session,$woocommerce;
	if(isset($_SESSION['step3']) &&  $_SESSION['step3'] == 'visit') {
		
		$_SESSION['step4'] = 'visit';
	}
	else {
		$_SESSION['msg'] = "Please complete step2";
		// One value
		$redirect = home_url('/step-3');
		wp_redirect($redirect);
		exit;
	}

	
	
}

function wcsAddToCart() {
    global $woocommerce;
	
	
    
    $product_id = (int)$_POST['product_id'];
    $quantity = 1;
    
    $variation_id = 0;
    
    if($variation_id <= 0) {
      $variation_id = null; $variation = null;
    } else {
      $variation = array_filter($_POST['attribute'], 'addslashes');
    }
    $html = '';
    $woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation );
    
    if( $woocommerce->cart ) {
    
    $items_in_cart = $woocommerce->cart->cart_contents_count;
    $prod_ids_in_cart = array();
    
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
    			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    			array_push($prod_ids_in_cart, $product_id );
    	}
    	
    $test01 = 0;
    $test02 = 0;
    $test03 = 0;
    $test04 = 0;
      
      if(in_array("566", $prod_ids_in_cart)) {
          $test01 = 1;
      }
      if(in_array("568", $prod_ids_in_cart)) {
          $test02 = 1;
      }
      if(in_array("570", $prod_ids_in_cart)) {
          $test03 = 1;
      }
      if(in_array("572", $prod_ids_in_cart)) {
          $test04 = 1;
      }
      
    $how_many_tests_in_cart = $test01 + $test02 + $test03 + $test04;
    
    if (in_array("574", $prod_ids_in_cart)) { 
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
     if($cart_item['product_id'] == 574 ){
        $woocommerce->cart->set_quantity( $cart_item_key, 0);
     }
    }
		
				
        if($how_many_tests_in_cart == 0) {
        }
        if($how_many_tests_in_cart == 1) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '719' , '1' );
			$_product = new WC_Product_Variation( 719 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 2) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '720' , '2' );
			$_product = new WC_Product_Variation( 720 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 3) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '721' , '3' );
			$_product = new WC_Product_Variation( 721 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 4) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '722' , '4' );
			$_product = new WC_Product_Variation( 722 );
			$price_urgent = $_product->get_price_html();
        }
    } // end if there is processing in cart
			
			/*$product = new WC_Product( $product_id );
			if($product_id != '574') {
			$price = $product->price;
			}*/
			if($woocommerce->cart->get_cart_contents_count() > 0) {
		//$html = '<ul class="cart_list product_list_widget">';
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
    			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product = new WC_Product( $product_id );
				if($product_id == 574) {
						$price = $price_urgent;
				}
				else {
					$price = $product->get_price_html();
				}
				
				//$product = new WC_Product( $product_id );				
				$html .='<tr id="product_id_'.$product_id.'"><td class="item_name">'.$product->post->post_title.'</td><td class="item_price">'.$price.'</td></tr>';
    }
	$html .='<tr><td class="item_name"><strong>Subtotal:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_subtotal().'</span></td></tr>';
	foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) : 
    		$code_cc = "'$code'";
    				$label = apply_filters( 'woocommerce_cart_totals_coupon_label', esc_html( __( 'Coupon:', 'woocommerce' ) . ' ' . $coupon->code ), $coupon );
 $html .='<tr class="cart-discount coupon-'.esc_attr( sanitize_title( $code ) ).'"><th>'.$label.'</th><td class="item_price">'.wc_custom_cart_totals_coupon_html($coupon).'</td></tr>';
		endforeach; 
	$html .='<tr><td class="item_name"><strong>Total:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_total().'</span></td></tr>';
	}
	
			else {
				$html .= '<tr><td class="item_name">No product added</td></tr>';
			}
	//$html .= '<p class="total"><strong>Subtotal:</strong> <span class="amount">'.$woocommerce->cart->get_cart_total().'</span></p>';
    } // edn of if woocommerce cart


      $coupon_code = 'test12'; 
 
   // if ( $woocommerce->cart->has_discount( $coupon_code ) ) return;
   // $woocommerce->cart->add_discount( $coupon_code );

     //  $woocommerce->cart->remove_coupons( $coupon_code );
   


   echo $html.'##'.$woocommerce->cart->get_cart_contents_count();
	die;
}


	
	public function removeToCart() {
		
		
      global $wpdb,$woocommerce;

       // Cycle through each product in the cart
      $prod_to_remove = $_POST['remove_item'];
   
        foreach( $woocommerce->cart->get_cart() as $cart_item_key => $prod_in_cart ) {
            // Get the Variation or Product ID
            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];
           
            	if($prod_in_cart['product_id'] == $prod_to_remove) {
            	
            	//$prod_unique_id = $woocommerce->cart->generate_cart_id( $prod_id );
            	$woocommerce->cart->set_quantity( $cart_item_key, 0 );
            }
            	
           
        }
   
   
    
    if( $woocommerce->cart ) {
    
    $items_in_cart = $woocommerce->cart->cart_contents_count;
    $prod_ids_in_cart = array();
    
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
          $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
          array_push($prod_ids_in_cart, $product_id );
      }
      
    $test01 = 0;
    $test02 = 0;
    $test03 = 0;
    $test04 = 0;
      
      if(in_array("566", $prod_ids_in_cart)) {
          $test01 = 1;
      }
      if(in_array("568", $prod_ids_in_cart)) {
          $test02 = 1;
      }
      if(in_array("570", $prod_ids_in_cart)) {
          $test03 = 1;
      }
      if(in_array("572", $prod_ids_in_cart)) {
          $test04 = 1;
      }
      
    $how_many_tests_in_cart = $test01 + $test02 + $test03 + $test04;
    
    if (in_array("574", $prod_ids_in_cart)) { 
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
     if($cart_item['product_id'] == 574 ){
        $woocommerce->cart->set_quantity( $cart_item_key, 0);
     }
    }
    
        if($how_many_tests_in_cart == 0) {
        }
         if($how_many_tests_in_cart == 1) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '719' , '1' );
			$_product = new WC_Product_Variation( 719 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 2) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '720' , '2' );
			$_product = new WC_Product_Variation( 720 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 3) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '721' , '3' );
			$_product = new WC_Product_Variation( 721 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 4) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '722' , '4' );
			$_product = new WC_Product_Variation( 722 );
			$price_urgent = $_product->get_price_html();
        }
    } // end if there is processing in cart
    if($woocommerce->cart->get_cart_contents_count() > 0) {
    //$html = '<ul class="cart_list product_list_widget">';
	
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
    			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product = new WC_Product( $product_id );
				if($product_id == 574) {
						$price = $price_urgent;
				}
				else {
					$price = $product->get_price_html();
				}
				
				//$product = new WC_Product( $product_id );				
				$html .='<tr id="product_id_'.$product_id.'"><td class="item_name">'.$product->post->post_title.'</td><td class="item_price">'.$price.'</td></tr>';
    }
	$html .='<tr><td class="item_name"><strong>Subtotal:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_subtotal().'</span></td></tr>';
	foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) : 
    		$code_cc = "'$code'";
    				$label = apply_filters( 'woocommerce_cart_totals_coupon_label', esc_html( __( 'Coupon:', 'woocommerce' ) . ' ' . $coupon->code ), $coupon );
 $html .='<tr class="cart-discount coupon-'.esc_attr( sanitize_title( $code ) ).'"><th>'.$label.'</th><td class="item_price">'.wc_custom_cart_totals_coupon_html($coupon).'</td></tr>';
		endforeach; 
	$html .='<tr><td class="item_name"><strong>Total:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_total().'</span></td></tr>';
	}
			else {
				$html .= '<tr><td class="item_name">No product added</td></tr>';
			}
	//$html .= '<p class="total"><strong>Subtotal:</strong> <span class="amount">'.$woocommerce->cart->get_cart_total().'</span></p>';
    
    } // edn of if woocommerce cart
	
	if($woocommerce->cart->get_cart_contents_count() == 1 && $product_id == '576') {
		$urgent = 0;	
	}
	else {
		$urgent == 1;
	}
	echo $html.'##'.$woocommerce->cart->get_cart_contents_count().'##'.$urgent;
    die;
		
	}

function change_client_info() {
	global $wpdb, $session;

	$user_ID = get_current_user_id();
	$client_first_name = $_POST['client_first_name'];
	$client_last_name = $_POST['client_last_name'];
	$client_first_name = update_user_meta( $user_ID, 'client_first_name', $client_first_name ); 
	$client_last_name = update_user_meta( $user_ID, 'client_last_name', $client_last_name );
	unset($_SESSION['checkout']);
	echo true;
	die;
}	
	
function wcsCart() {
    global $woocommerce;
	
	
    $message = '';
       
   
	if(isset($_POST['remove_coupn']) && $_POST['remove_coupn'] != '') {
		$coupon_code = $_POST['remove_coupn'];

       // Coupon is no longer valid, based on date.  Remove it.
            if ($woocommerce->cart->has_discount(sanitize_text_field($coupon_code))) {

                if ($woocommerce->cart->remove_coupons(sanitize_text_field($coupon_code))) {

                    $woocommerce->clear_messages();
                    

                }
                $message = $coupon_code." code remove succesfully";
                // Manually recalculate totals.  If you do not do this, a refresh is required before user will see updated totals when discount is removed.
                $woocommerce->cart->calculate_totals();

            }

	}
	if(isset($_POST['add_coupon']) && $_POST['add_coupon'] != '') {
		$coupon_code = $_POST['add_coupon'];
				//if ( $woocommerce->cart->has_discount( $coupon_code ) ) return;
			 $woocommerce->cart->add_discount( $coupon_code );
			 if( $woocommerce->cart->applied_coupons) {
			 	$message = $coupon_code." code successfully applied";
			 }
			 else {
			 	$message = $coupon_code." code does not exist";
			 }

			
			$woocommerce->cart->calculate_totals();
			  
	}

    if( $woocommerce->cart ) {
    
    $items_in_cart = $woocommerce->cart->cart_contents_count;
    $prod_ids_in_cart = array();
    
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
    			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
    			array_push($prod_ids_in_cart, $product_id );
    	}
    	
    $test01 = 0;
    $test02 = 0;
    $test03 = 0;
    $test04 = 0;
      
      if(in_array("566", $prod_ids_in_cart)) {
          $test01 = 1;
      }
      if(in_array("568", $prod_ids_in_cart)) {
          $test02 = 1;
      }
      if(in_array("570", $prod_ids_in_cart)) {
          $test03 = 1;
      }
      if(in_array("572", $prod_ids_in_cart)) {
          $test04 = 1;
      }
      
    $how_many_tests_in_cart = $test01 + $test02 + $test03 + $test04;
    
    if (in_array("574", $prod_ids_in_cart)) { 
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
     if($cart_item['product_id'] == 574 ){
        $woocommerce->cart->set_quantity( $cart_item_key, 0);
     }
    }
		
				
        if($how_many_tests_in_cart == 0) {
        }
        if($how_many_tests_in_cart == 1) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '719' , '1' );
			$_product = new WC_Product_Variation( 719 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 2) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '720' , '2' );
			$_product = new WC_Product_Variation( 720 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 3) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '721' , '3' );
			$_product = new WC_Product_Variation( 721 );
			$price_urgent = $_product->get_price_html();
        }
        if($how_many_tests_in_cart == 4) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '722' , '4' );
			$_product = new WC_Product_Variation( 722 );
			$price_urgent = $_product->get_price_html();
        }
    } // end if there is processing in cart
			
			/*$product = new WC_Product( $product_id );
			if($product_id != '574') {
			$price = $product->price;
			}*/
			$html = '';
			if($woocommerce->cart->get_cart_contents_count() > 0) {
		//$html = '<ul class="cart_list product_list_widget">';
    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
    			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product = new WC_Product( $product_id );
				if($product_id == 574) {
						$price = $price_urgent;
				}
				else {
					$price = $product->get_price_html();
				}
				
				$product = new WC_Product( $product_id );				
				//$html .='<li class="mini_cart_item" id="product_id_'.$product_id.'"><a href="javascript:void(0);">'.$product->post->post_title.' ( '. $price.')	</a>							</li>';
							$html .='<tr id="product_id_'.$product_id.'"><td class="item_name">'.$product->post->post_title.'</td><td class="item_price">'.$price.'</td></tr>';
    }
    	

	$html .='<tr><td class="item_name"><strong>Subtotal:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_subtotal().'</span></td></tr>';
	foreach ( $woocommerce->cart->get_coupons() as $code => $coupon ) : 
    		$code_cc = "'$code'";
    				$label = apply_filters( 'woocommerce_cart_totals_coupon_label', esc_html( __( 'Coupon:', 'woocommerce' ) . ' ' . $coupon->code ), $coupon );
 $html .='<tr class="cart-discount coupon-'.esc_attr( sanitize_title( $code ) ).'"><th>'.$label.'</th><td class="item_price">'.wc_custom_cart_totals_coupon_html($coupon).'</td></tr>';
		endforeach; 
	$html .='<tr><td class="item_name"><strong>Total:</strong></td><td class="item_price"><span class="amount">'.$woocommerce->cart->get_cart_total().'</span></td></tr>';
    //$html .= '</ul>';
			}
			else {
				$html .= '<tr><td class="item_name">No product added</td></tr>';
			}
	//$html .= '<p class="total"><strong>Subtotal:</strong> <span class="amount">'.$woocommerce->cart->get_cart_total().'</span></p>';
    } // edn of if woocommerce cart
	//wc_clear_notices();
   echo $html.'##'.$woocommerce->cart->get_cart_contents_count().'##'.wc_print_notices();
	die;
}
	
public function check_product_exist() {
	global $wpdb,$woocommerce;
	$product_array = array();
	foreach( $woocommerce->cart->get_cart() as  $cart_item_key => $cart_item ) {

		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$product_array[] = $product_id;
		
	}
	return $product_array;
	
}



/* redirect user to your custom page after clicking "place order" button 
function wc_custom_redirect_after_purchase() {
	global $wp;
	
	if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
		wp_redirect( home_url('/step-5') );
		exit;
	}
}*/
public function custom_override_checkout_fields( $fields ) {
	unset($fields['billing']['billing_first_name']);
unset($fields['billing']['billing_last_name']);
unset($fields['billing']['billing_email']);
unset($fields['billing']['billing_phone']);

return $fields;
}



function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Steps Sidebar', 'wcs' ),
        'id' => 'steps-sidbar',
        'description' => __( 'Widgets in this area will be shown on all step pages.', 'theme-slug' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>',
    ) );
}

public function get_image($id) {
	$img_array = array();
	
	$img_array[566] = 'Food_half.jpg';
	$img_array[568] = 'Heart_half.jpg';
	$img_array[570] = 'Toxic_half.jpg';
	$img_array[572] = 'Vitamin_full.jpg';
	
	return $img_array[$id];	
}


}

function wcs_plugins_loaded_register(){
    new Frontpage;
}
add_action( 'plugins_loaded', 'wcs_plugins_loaded_register' ); 


function wc_custom_cart_totals_coupon_html( $coupon ) {
  if ( is_string( $coupon ) ) {
    $coupon = new WC_Coupon( $coupon );
    }

  $value  = array();

  if ( $amount = WC()->cart->get_coupon_discount_amount( $coupon->code, WC()->cart->display_cart_ex_tax ) ) {
    $discount_html = '-' . wc_price( $amount );
  } else {
    $discount_html = '';
  }

  $value[] = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_html, $coupon );

  if ( $coupon->enable_free_shipping() ) {
    $value[] = __( 'Free shipping coupon', 'woocommerce' );
    }

    // get rid of empty array elements
    $value = array_filter( $value );
    $code_cc = "'$coupon->code'";
  $value = implode( ', ', $value ) . ' <a href="javascript:void(0);" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->code ) . '" onclick="remove_coupon('.$code_cc.')">' . __( '[X]', 'woocommerce' ) . '</a>';

  return apply_filters( 'woocommerce_cart_totals_coupon_html', $value, $coupon );
}
add_filter('woocommerce_get_country_locale', 'wpse_120741_wc_change_state_label_locale');
function wpse_120741_wc_change_state_label_locale($locale){
  
    $locale['NZ']['state']['label'] = __('City', 'woocommerce');
    $locale['NZ']['city']['label'] = __('Suburb', 'woocommerce');
    $locale['NZ']['city']['placeholder'] = __('Suburb', 'woocommerce');
    $locale['NZ']['address_2']['placeholder'] = __('', 'woocommerce');
    $locale['NZ']['postcode']['label'] = __('Postcode', 'woocommerce');
    $locale['NZ']['postcode']['placeholder'] = __('Postcode', 'woocommerce');
 
    return $locale;
}

/**
 * Changes the redirect URL for the Return To Shop button in the cart.
 *
 * @return string
 */
function wc_empty_cart_redirect_url() {
	$redirect = home_url('/step-3');
	return $redirect;
}
add_filter( 'woocommerce_return_to_shop_redirect', 'wc_empty_cart_redirect_url' );

/*
add_filter( 'woocommerce_get_country_locale', 'use_only_default_locale' );
function use_only_default_locale( $locale ) {
  return array();
}*/


add_action('init','return_to_order');
function return_to_order() {
	if(!isset($_SESSION['checkout']) && $_SESSION['step4'] == 'visit' && $_SESSION['step3'] == 'visit' && $_SESSION['checkout'] == '') {
		add_filter( 'wp_nav_menu_items', 'your_custom_menu_item', 10, 2 );
	}
	
}

function your_custom_menu_item ( $items, $args ) {
	$menu = $items;
    if ($args->theme_location == 'primary') {
        $items = '<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-584 return_to_order" id="menu-item-1170"><a href="'.get_permalink(1170).'" class="btn">Return to Order</a></li>';
    }
    $items .= $menu;
    return $items;
}
