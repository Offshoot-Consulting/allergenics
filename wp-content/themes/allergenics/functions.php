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
  /*  wp_deregister_script('wc-add-to-cart-variation');
    wp_register_script('wc-add-to-cart-variation', get_bloginfo( 'template_directory' ). '/woocommerce/assets/js/frontend/add-to-cart-variation.min.js' , array( 'jquery' ), WC_VERSION, TRUE);
wp_enqueue_script('wc-add-to-cart-variation');

wp_deregister_script('wc-single-product');
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
?>
