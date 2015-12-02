<?php
 /*
 	Plugin Name: Woocommerce Step Checkout
 	Plugin URI: https://allergenicstesting.devsite.co.nz
 	Description: Woocommerce Step Checkout
 	Version: 1.0.6
 	Author:Jay Thakur
 	Author URI: https://allergenicstesting.devsite.co.nz
 */
define( 'AT_WSC_ANYONE_VER', '1.0.6' );
define( 'AT_WSC_ANYONE_DB_VER', '1.0.6' );
global $wpdb;

global $wp_step_checkout_db_version;
$wp_step_checkout_db_version = '1.1'; // version changed from 1.0 to 1.1

global $wpdb;

global $wp_step_checkout_db_version;
$wp_step_checkout_db_version = '1.1'; // version changed from 1.0 to 1.1
require_once 'controllers/front_template.php';
require_once 'controllers/pagetemplater.php';


function wcs_enqueue_scripts(){

	
}
add_action( 'admin_init', 'wcs_enqueue_scripts');

function wcs_enqueue_css(){

	wp_enqueue_style( 'wcs-css',plugins_url('assets/css/front.css',__FILE__ ) );
	
	
	wp_register_script( "wcs-js", plugins_url('assets/js/script.js',__FILE__ ), array(), '1.0.0', true );
	wp_localize_script( 'wcs-js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));    
	wp_enqueue_script( 'wcs-js' );
	
}
add_action( 'init', 'wcs_enqueue_css');




/**
 * register_activation_hook implementation
 *
 * will be called when user activates plugin first time
 * must create needed database tables
 */
function table_example_install()
{

	
	$pages = array();
	//$pages[0]['title'] = 'Start Your Order Right Now';
	//$pages[0]['slug'] = 'order-your-test-now';
	//$pages[0]['template'] = 'order-your-test-now.php';
	//$pages[0]['content'] = '';
	$pages[0]['title'] = 'Personal Details';
	$pages[0]['slug'] = 'step-1';
	$pages[0]['template'] = 'step1.php';
	$pages[0]['content'] = '[woocommerce_my_account]';
	$pages[1]['title'] = 'Information form';
	$pages[1]['slug'] = 'step-2';
	$pages[1]['template'] = 'step2.php';
	$pages[1]['content'] = '';
	$pages[2]['title'] = 'Select Your Test';
	$pages[2]['slug'] = 'step-3';
	$pages[2]['template'] = 'step3.php';
	$pages[2]['content'] = '';
	$pages[3]['title'] = 'Pay';
	$pages[3]['slug'] = 'step-4';
	$pages[3]['template'] = 'step4.php';
	$pages[3]['content'] = '[woocommerce_checkout]';
	$pages[4]['title'] = 'Order Recived';
	//$pages[4]['slug'] = 'step-5';
	//$pages[4]['template'] = 'step5.php';
	//$pages[4]['content'] = '';
	$i = 0;
	foreach($pages as $page) {
		$new_page_title = $page['title'];
		$new_page_slug = $page['slug'];
		$new_page_content = $page['content'];
		$new_page_template = $page['template'];

		//don't change the code bellow, unless you know what you're doing

		$page_check = get_page_by_path($new_page_slug);
	
		$new_page = array(
			'post_type' => 'page',
			'post_title' => $new_page_title,
			'post_name' => $new_page_slug,
			'post_content' => $new_page_content,
			'post_status' => 'publish',
			'post_author' => 1,
		);
	
		if(!isset($page_check->ID)){
			$new_page_id = wp_insert_post($new_page);
			if($i == 3) {
				$chekout_page_id = $new_page_id;
				update_option( 'woocommerce_checkout_page_id', $chekout_page_id );
			}
			if(isset($new_page_template)){
				update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
			}
		}
	$i++;}
}

register_activation_hook(__FILE__, 'table_example_install');


function plp_add_to_menu() {
	
	add_menu_page(__('Woocommerce Step Checkout', 'wcs'), __('Woocommerce Step Checkout', 'wcs'), 'activate_plugins', 'wcs-setting', 'manage_settings');
   	add_submenu_page('wcs-setting', __('Settings', 'wcs'), __('Settings', 'wcs'), 'activate_plugins', 'wcs-setting', 'manage_settings');
   
	
}


add_action('admin_menu', 'plp_add_to_menu');


function manage_settings() {

    global $wpdb;
	require_once 'controllers/admin/manage-settings.php';
  

	
}