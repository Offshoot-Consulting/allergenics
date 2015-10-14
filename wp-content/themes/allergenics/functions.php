<?php
/*
	DO NOT PUT FUNCTION CODE DIRECTLY IN THIS FILE. EITHER APPEND YOUR FUNCTION TO ONE OF THE FILES THAT ALREADY EXSITS IF AN APPROPRIATE FILE EXISTS, 
	OR OTHER WISE CREATE A NEW PHP FILE IN THE FUNCTION_INCLUDES DIRECTORY AND THE ADD A SINLE REQQUIRE LINE IN THIS FILE.
	
	MAKE SURE YOU PROVIDE CLEAR PLAIN ENGLISH COMMENTS IN YOUR PHP FILES TO EXPLAIN WHAT YOUR FUCNTION DOES
*/

/** Timer code
function microtime_float()
{
     list($usec, $sec) = explode(" ", microtime());
     return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();
//do something here
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Took $time seconds\n";

 */

/** Put required files below */
require 'function_includes/check_if_this_is _staging_site.php';

require 'function_includes/start_session.php';

include( get_template_directory() . '/widgets.php' );

require 'function_includes/general_theme_stuff.php';

require 'function_includes/Gravity_form_functions.php';

require 'function_includes/Team_section_functions.php';



/* woocommerce customization */

//$time_end = microtime_float();

//$time = $time_end - $time_start;
//echo "Took $time seconds\n";

 remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

/* start customization in woocommerce theme */
/* remove sku from prodtc page */

add_filter( 'wc_product_sku_enabled', '__return_false' );

/** 
 * Change on single product panel "Product Description"
 * since it already says "features" on tab.
 */
add_filter('woocommerce_product_description_heading',
'isa_product_description_heading');
 
function isa_product_description_heading() {
    return __('', 'woocommerce');
}
// Use WC 2.0 variable price format, now include sale price strikeout
add_filter( 'woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2 );
function wc_wc20_variation_price_format( $price, $product ) {
    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice ) {
        $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
    }
    return $price;
}


add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {

	$tabs['description']['title'] = __( 'What we test for' );		// Rename the description tab
	return $tabs;

}

add_action('wp_enqueue_scripts', 'override_woo_frontend_scripts');
function override_woo_frontend_scripts() {
  wp_deregister_script('wc-add-to-cart-variation');
    wp_register_script('wc-add-to-fly', get_bloginfo( 'template_directory' ). '/js/orakatc.js' , array( 'jquery' ), WC_VERSION, TRUE);
wp_enqueue_script('wc-add-to-fly');

/*wp_deregister_script('wc-single-product');
    wp_register_script('wc-single-product', get_bloginfo( 'template_directory' ). '/woocommerce/assets/js/frontend/single-product.js' , array( 'jquery' ), WC_VERSION, TRUE);
wp_enqueue_script('wc-single-product');*/
 
}

// Removes shipping method labels
  
add_filter( 'woocommerce_cart_shipping_method_full_label', 'remove_label', 10, 2 );
  
function remove_label($label, $method) {
$new_label = preg_replace( '/^.+:/', '', $label );
return $new_label;
}

/*remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
function woocommerce_template_product_description() {
woocommerce_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_product_description', 20 );*/

/* Change add to cart button text */
add_filter( 'add_to_cart_text', 'woo_custom_cart_button_text' );    // < 2.1

function woo_custom_cart_button_text() {
 
        return __( 'ADD TO CART', 'woocommerce' );
 
 }
 add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );    // 2.1 +
 
function woo_custom_single_add_to_cart_text() {
 
        return __( 'ADD TO CART', 'woocommerce' );
 
}

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text' );    // 2.1 +
 
function woo_archive_custom_cart_button_text() {
 
        return __( 'ADD TO CART', 'woocommerce' );
 
}

/* remove The snippet: remove “default sorting” dropdown from shop and cat pages */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/** Remove Showing results functionality site-wide */
function woocommerce_result_count() {
        return;
}
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 ); // remove product category from single product page



add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

add_action('wp_head','hook_css');

function hook_css() {
	global $wpdb;
	if(is_checkout()) {
	$output="<style> .woocommerce .woocommerce-info:nth-of-type(2) { display:none; } .woocommerce-shipping-fields { display:block; } .woocommerce table.shop_table tr.cart_item td:nth-of-type(2) { text-align:center; } .woocommerce table.shop_table tr.order-total td { text-align:center; } .woocommerce table.shop_table tr.cart-subtotal td { text-align:center; }</style>";

	echo $output;
	}

}
add_filter( 'woocommerce_billing_fields', 'custom_woocommerce_billing_fields',-1 );

function custom_woocommerce_billing_fields( $fields ) {

   // Over-ride a single label
   $fields['billing_first_name']['label'] = 'Your label';
   
   // Over-ride a single required value
   $fields['billing_first_name']['required'] = false;

   // Over-ride the entire thing
   $fields['billing_postcode']	= array( 
      'label'          => __('Postcode', 'woothemes'), 
      'placeholder'    => __('Postcode', 'woothemes'), 
      'required'       => true, 
      'class'          => array('form-row-last update_totals_on_change') 
   );

   /**
    * You can over-ride -  billing_first_name, billing_last_name, billing_company, billing_address_1, billing_address_2, billing_city, billing_postcode, billing_country, billing_state, billing_email, billing_phone
    */
   
   return $fields;
}
/*add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
	global $wpdb,$woocommerce;
   if ($args->menu == 'top-head-menu') {
	$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
	if ( $myaccount_page_id ) {
	$logout_url = wp_logout_url( get_permalink( $myaccount_page_id ) );
	if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' )
	$logout_url = str_replace( 'http:', 'https:', $logout_url );
	}

      if (is_user_logged_in()) {
         $items .= '<li class="right"><a href="'. $logout_url .'">Log Out</a></li>';
		
      } 
	  // $items .= '<li class="search-btn">'.get_product_search_form().'</li>';
   }
   return $items;
}*/

add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );

/**
 * woo_custom_product_searchform
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function woo_custom_product_searchform( $form ) {
	
		$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div class="hit_me">
			
			<input type="text" class="seach_text" value="' . get_search_query() . '" autofocus="autofocus" name="s" id="s" placeholder="' . __( 'Search Product', 'woocommerce' ) . '" />
			<input type="button" id="searchgly" value="" />
		
		</div>
	</form>';
	
	/*$form = '<form role="search" method="get" id="searchform" class="searchform" action="">
				<div class="hit_me">
					
					<input type="text" value="" name="s" id="s" />
					<input type="button" id="searchgly" value="">
					
				</div>
			</form>';*/
	
	return $form;
	
}
function me() {
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
}

function custom_pre_get_posts_query( $q ) {

	if ( ! $q->is_main_query() ) return;
	if ( ! $q->is_post_type_archive() ) return;
	
	if ( ! is_admin() && is_shop() ) {

		$q->set( 'tax_query', array(array(
			'taxonomy' => 'product_cat',
			'field' => 'id',
			'terms' => array( '13' ), // Don't display products in the knives category on the shop page
			'operator' => 'IN'
		)));
	
	}

	remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );

}

add_filter( 'woocommerce_checkout_fields' , 'custom_wc_checkout_fields',999 );
 
// Change order comments placeholder and label, and set billing phone number to not required.
function custom_wc_checkout_fields( $fields ) {
$fields['billing']['billing_first_name']['placeholder'] = 'First Name *';
//$fields['billing']['billing_first_name']['label'] = '';
$fields['billing']['billing_last_name']['placeholder'] = 'Last Name *';
//$fields['billing']['billing_last_name']['label'] = '';
$fields['billing']['billing_email']['placeholder'] = 'Email Address *';
//$fields['billing']['billing_email']['label'] = '';
$fields['billing']['billing_phone']['placeholder'] = 'Phone *';
//$fields['billing']['billing_phone']['label'] = '';
$fields['billing']['billing_country']['placeholder'] = 'Country *';
$fields['billing']['billing_country']['label'] = '';
$fields['billing']['billing_address_1']['label'] = '';
$fields['billing']['billing_state']['label'] = '';
$fields['billing']['billing_postcode']['label'] = '';
$fields['billing']['billing_city']['label'] = '';
//$fields['account']['account_password']['label'] = '';
return $fields;
}

add_filter( 'woocommerce_order_button_text', create_function( '', 'return "Make Payment";' ),999 );


// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}

add_action('woocommerce_add_to_cart','before_add_to_cart');
function before_add_to_cart() {
	global $wpdb,$woocommerce,$cart;
	ob_start();
	// assumes $to, $subject, $message have already been defined earlier...

$headers[] = 'From: Me Myself <me@example.net>';
$headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
$headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address
$message =  '';
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
	$message .= $cart_item['product_id'];
	$message .= '<br>';
}
/*$product_id = 574;
		$quantity = 1;
		$variation_id = 719;
		$variation  = 1;
		$woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation );*/
			
	//$message .= WC()->cart->cart_contents_count;

$to = "seth@mailinator.com";
$subject = 'Woocommerce';
wp_mail( $to, $subject, $message, $headers );

}


function remove_loop_button(){
remove_all_actions('wp_ajax_nopriv_orak_add_to_cart');
    remove_all_actions('wp_ajax_orak_add_to_cart');
}
add_action('init','remove_loop_button');

add_action('wp_ajax_orak_add_to_cart_fly',  'addToCartFly',10 );
		add_action('wp_ajax_nopriv_orak_add_to_cart_fly', 'addToCartFly',10 );
    
	

		
function addToCartFly() {
    global $woocommerce;
    
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    $variation_id = (int)$_POST['variation_id'];
    
    if($variation_id <= 0) {
      $variation_id = null; $variation = null;
    } else {
      $variation = array_filter($_POST['attribute'], 'addslashes');
    }
    
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
        }
        if($how_many_tests_in_cart == 2) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '720' , '2' );
        }
        if($how_many_tests_in_cart == 3) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '721' , '3' );
        }
        if($how_many_tests_in_cart == 4) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '722' , '4' );
        }
    } // end if there is processing in cart
    
    
    
    } // edn of if woocommerce cart
    
    die();
}


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
//add_action( 'woocommerce_order_status_processing','mysite_woocommerce_order_status_completed' );


function add_test_into_backend( $order_id ) {
$IsUrgent = false;
    $order = new WC_Order( $order_id );
    $data_submit = get_post_meta($order_id,'data_submit',true);
  
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
   
  }
  update_post_meta($order_id,'data_submit','Y');
}
}


add_action( 'woocommerce_cart_item_removed', 'action_woocommerce_cart_item_removed', 10, 1 );

function refresh_cart_content() {

    global $woocommerce;
    $cart_url = $woocommerce->cart->get_cart_url();
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
        }
        if($how_many_tests_in_cart == 2) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '720' , '2' );
        }
        if($how_many_tests_in_cart == 3) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '721' , '3' );
        }
        if($how_many_tests_in_cart == 4) {
            $woocommerce->cart->add_to_cart( '574' , '1' , '722' , '4' );
        }
    } // end if there is processing in cart
    
    
    
    } 

    header("Refresh:0; url=".$cart_url);
    exit;

}

?>
