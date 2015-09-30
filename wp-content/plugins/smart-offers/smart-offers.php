<?php
/**
 * Plugin Name: Smart Offers
 * Plugin URI: http://www.storeapps.org/product/smart-offers/
 * Description: <strong>Smart Offers</strong> lets you earn more by creating a powerful sales funnel of upsells, downsells and backend offers. Show special offers during checkout or on my account page.
 * Version: 3.1.5
 * Author: Store Apps
 * Author URI: http://www.storeapps.org/
 * Requires at least: 3.3
 * Tested up to: 4.3
 * Text Domain: smart-offers
 * Domain Path: /languages/
 * Copyright (c) 2013, 2014, 2015 Store Apps
 */

if (!defined('ABSPATH')) {
	exit;
}

$active_plugins = (array) get_option('active_plugins', array());

if (is_multisite()) {
	$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
}

if (!( in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) )) {
	return;
} else {

	if (!class_exists('SA_Smart_Offers')) {

		class SA_Smart_Offers {

			static $text_domain;

			function __construct() {
				global $wpdb;

				$this->define_constants();
				$this->includes();

				add_action( 'init', array($this, 'init') );
				add_action( 'init', array($this, 'localize') );

				if (is_admin()) {
					$this->initialize_so_upgrade();
				}
			}


			/**
			 * to handle WC compatibility related function call from appropriate class
			 * 
			 * @param $function_name string
			 * @param $arguments array of arguments passed while calling $function_name
			 * @return result of function call
			 * 
			 */
			public function __call( $function_name, $arguments = array() ) {

				if ( ! is_callable( 'SA_WC_Compatibility_2_3', $function_name ) ) return;

				if ( ! empty( $arguments ) ) {
					return call_user_func_array( 'SA_WC_Compatibility_2_3::'.$function_name, $arguments );
				} else {
					return call_user_func( 'SA_WC_Compatibility_2_3::'.$function_name );
				}

			}

			/**
			 * Function to be executed on activation
			 */
			public static function so_activate() {

				global $wpdb, $blog_id;

	            //For multisite table prefix
	            if ( is_multisite() ) {
	                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}", 0 );
	            } else {
	                $blog_ids = array( $blog_id );
	            }

	            foreach ( $blog_ids as $blog_id ) {

	                $wpdb_obj = clone $wpdb;
	                $wpdb->blogid = $blog_id;
	                $wpdb->set_prefix( $wpdb->base_prefix );
	                
	                if ( get_option( '_current_smart_offers_db_version' ) === false ) {

	                    self::upgrade_database_for_3_1_2();
	                }

	                update_option( '_current_smart_offers_db_version', '3.1.2' );

	                $wpdb = clone $wpdb_obj;

	            }

				include_once( 'includes/admin/class-so-admin-post-type.php' );
				include_once( 'includes/admin/class-so-admin-install.php' );
        	}

        	/**
		     * Database updation for version 3.1.2 for merging Before Checkout & Checkout
		     * 
		     * @global wpdb $wpdb WordPress Database Object
		     */
			public static function upgrade_database_for_3_1_2() { 

		        global $wpdb;

	            $pre_checkout_page_rule = 'offer_rule_pre_checkout_page';
	            $so_page_options = 'offer_rule_page_options';

		        $smart_offers_ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key IN ( %s, %s )", $pre_checkout_page_rule, $so_page_options ) );

		        if ( empty( $smart_offers_ids ) ) return;

		        add_option( 'smart_offers_ids_pre_checkout', $smart_offers_ids );

	            $update_page_result = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_key = 'offer_rule_checkout_page' WHERE meta_key = %s", $pre_checkout_page_rule ) );
	            $update_page_options_result = $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, 'pre_checkout_page', 'checkout_page') WHERE meta_key = %s", $so_page_options ) );

	            if ( $update_page_result !== false && $update_page_options_result !== false ) {
	                delete_option( 'smart_offers_ids_pre_checkout' );
	            }

		        update_option( '_current_smart_offers_db_version', '3.1.2' );

		    }

			/*
			 * Include class files
			 */
			function includes() {
				global $sa_smart_offers;

				include_once ( 'classes/class-wc-compatibility.php' );
				include_once ( 'classes/class-wc-compatibility-2-2.php' );
				include_once ( 'classes/class-wc-compatibility-2-3.php' );
				
				if ( ! $sa_smart_offers instanceof SA_Smart_Offers ) {
					$sa_smart_offers = $this;
				}

				if (is_admin()) {

					include_once 'includes/admin/class-so-admin-settings.php';
					include_once 'includes/admin/class-so-admin-welcome.php' ;
					include( 'includes/admin/class-so-admin-post-type.php' );
					include( 'includes/admin/class-so-admin-pointers.php' );
					// Post type
					include_once( 'includes/admin/class-so-admin-offer.php' );
					include_once( 'includes/admin/class-so-admin-offers.php' );
					include_once( 'includes/admin/class-so-admin-dashboard-widget.php' );
					include_once( 'includes/admin/class-so-admin-footer.php' );

					if ( ! class_exists( 'Store_Apps_Upgrade' ) ) {
						require_once 'sa-includes/class-storeapps-upgrade.php';
					}
				}

				if (!is_admin() || defined('DOING_AJAX')) {
					include_once( 'includes/frontend/class-so-shortcodes.php' );
					include_once( 'includes/frontend/class-so-session-handler.php' );
				}

				// In file class-so-init.php & class-so-offer.php, some stats are modified based on order statuses
				// and order statuses can be changed from admin side also, therefor kept open for both admin & frontend
				include_once( 'includes/frontend/class-so-offer.php' );
				include_once( 'includes/frontend/class-so-offers.php' );
				include_once( 'includes/frontend/class-so-init.php' );
				if ( ! function_exists( 'is_plugin_active' ) ) {
					$abspath = trailingslashit( ABSPATH );
					require_once ABSPATH  . 'wp-admin/includes/plugin.php';
				}
				if ( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
					include_once( 'includes/frontend/class-so-subscription.php' );
				}
			}

			/*
			 * Defining SO Constants
			 */
			private function define_constants() {

				define('SO_PLUGIN_FILE', __FILE__);
				define('SO_PLUGIN_BASENAME', plugin_basename( __FILE__) );
				define('SO_PLUGIN_DIRNAME', dirname( plugin_basename(__FILE__) ) );
				define('SMART_OFFERS', substr( plugin_basename( __FILE__ ), 0, strpos( plugin_basename( __FILE__ ), '/' ) ) );
				if (!defined('SO_TEXT_DOMAIN')) {
            		define('SO_TEXT_DOMAIN', 'smart-offers');						// During Code Cleanup, need to replace all text doamins as 'SO_TEXT_DOMAIN'
        		}
			}

			function get_sample_offers() {

				$offer_content = array (
								array(
										'post' => array(
														'ID'             => '',
														'post_content'   => '<div id="so_main" style="border: 3px dashed blue; height: 500px; width: 100%;">
																				<h1 align="center"><span style="color: red;">Amazing offer !!!</span></h1>
																				<div>
																				<h4 align="center">For just <em style="color: red; font-size: 25px;">$10</em> (Getting a flat discount of $5 on Actual price: <em style="color: red; font-size: 20px;">$15</em> )</h4>
																				<h2 style="text-align: center;">Click on <em style="background-color: yellow;">"Yes, Add to Cart"</em> to Avail</h2>
																				<div class="so_accept" style="text-align: center;"><a href="[so_acceptlink]">Yes, Add to Cart</a></div>
																				<div class="so_skip" style="text-align: center;"><a href="[so_skiplink]">No, Skip this</a></div>
																				</div>
																			</div>', 
														'post_name'      => 'sample-offer-1',
														'post_title'     => 'Sample Offer 1',
														'post_status'    => 'draft',
														'post_type'      => 'smart_offers',
														'post_excerpt'   => ''
													),
										'postmeta' => array(
														'discount_type' => 'fixed_price',
														'offer_price' => '10',
														'offer_rule_page_options' => 'checkout_page',
														'offer_rule_checkout_page' => 'yes',
														'so_show_offer_as' => 'offer_as_popup',
														'offer_rule_registered_user' => 'no',
														'_offer_rules' => array(
																			array(
																					'offer_type' => 'user',
																		            'offer_action' => 'registered_user',
																		            'offer_rule_value' => 'no'
																			)
																		)
													),
								),
								array(
										'post' => array(
													'ID'             => '',
													'post_content'   => '<h1 align="center">!!Anniversary Sale!!</h1>
																		<div>
																			<h4 align="center">It\'s Our Anniversary
																			And We Want to Splash a Party
																			Grab this @ exclusive 15% off and shop now</h4>
																			<div class="so_accept" style="text-align: center;"><a href="[so_acceptlink]">Heck Yes, I want this</a></div>
																			<div class="so_skip" style="text-align: center;"><a href="[so_skiplink]">No, I don\'t want this</a></div>
																		</div>', 
													'post_name'      => 'sample-offer-2',
													'post_title'     => 'Sample Offer 2',
													'post_status'    => 'draft',
													'post_type'      => 'smart_offers',
													'post_excerpt'   => ''
												),
										'postmeta' => array(
														'discount_type' => 'percent_discount',
														'offer_price' => '15',
														'offer_rule_page_options' => 'cart_page,myaccount_page',
														'offer_rule_cart_page' => 'yes',
														'offer_rule_myaccount_page' => 'yes',
														'so_show_offer_as' => 'offer_as_inline',
														'offer_rule_registered_user' => 'yes',
														'_offer_rules' => array(
																			array(
																					'offer_type' => 'user',
																		            'offer_action' => 'registered_user',
																		            'offer_rule_value' => 'yes'
																			)
																		)
													),
								),
								array(
										'post' => array(
													'ID'             => '',
													'post_content'   => '<div id="so_main">
																			<h1 align="center">GRAB\'EM ALL</h1>
																			<div>
																			<h4 align="center">Flat 50% Off on any product.
																			Offer Valid Until Stocks Last.</h4>
																			<div class="so_accept" style="text-align: center;"><a href="[so_acceptlink]">Yes, I\'m in</a></div>
																			<div class="so_skip" style="text-align: center;"><a href="[so_skiplink]">No, Skip this</a></div>
																			</div>
																		</div>', 
													'post_name'      => 'sample-offer-3',
													'post_title'     => 'Sample Offer 3',
													'post_status'    => 'draft',
													'post_type'      => 'smart_offers',
													'post_excerpt'   => ''
												),
										'postmeta' => array(
															'discount_type' => 'percent_discount',
															'offer_price' => '50',
															'offer_rule_page_options' => 'home_page',
															'offer_rule_home_page' => 'yes',
															'so_show_offer_as' => 'offer_as_popup',
															'offer_rule_offer_valid_between' => array(
																				                    'offer_valid_from' => '1433116800',
																				                    'offer_valid_till' => '1451520000'
													                							),
															'_offer_rules' => array(
																				array(
																						'offer_type' => 'offer_valid_between',
																			            'offer_action' => 'offer_valid_between',
																			            'offer_rule_value' => array(
																			            						'offer_valid_from' => '1433116800',
																				                    			'offer_valid_till' => '1451520000'		
																			            						)
																				)
																			)
														),
								),
								array(
										'post' => array(
														'ID'             => '',
														'post_content'   => '<h1 align="center"><span style="color: red;">Save a Ton!!!</span></h1>
																			<div>
																				<h4 align="center">Shop for 1000 and Get a Gift worth <span style="color: red;">250 Free</span>!!
																				Hurry up!</h4>
																				<div style="text-align: center;"><a href="[so_acceptlink]">Click to claim your FREE gift</a></div>
																				<div class="so_skip" style="text-align: center;"><a href="[so_skiplink]">No, I don\'t want it</a></div>
																			</div>', 
														'post_name'      => 'sample-offer-4',
														'post_title'     => 'Sample Offer 4',
														'post_status'    => 'draft',
														'post_type'      => 'smart_offers',
														'post_excerpt'   => ''
												),
										'postmeta' => array(
															'discount_type' => 'fixed_price',
															'offer_price' => '250',
															'offer_rule_page_options' => 'cart_page',
															'offer_rule_cart_page' => 'yes',
															'so_show_offer_as' => 'offer_as_inline',
															'offer_rule_cart_grand_total_more' => '1000',
															'_offer_rules' => array(
																			array(
																					'offer_type' => 'cartorder',
																		            'offer_action' => 'cart_grand_total_more',
																		            'offer_rule_value' => '1000'
																			)
																		)
														),
								),
						);
				return $offer_content;
			}

			function import_smart_offers( $args = array() ) {
				if ( empty( $args ) ) return;
				
				foreach ( $args as $arg ) {
					$post_id = wp_insert_post( $arg['post'] );
					foreach ( $arg['postmeta'] as $meta_key => $meta_value ) {
						update_post_meta( $post_id, $meta_key, $meta_value );
					}
				}
			}

			public static function get_smart_offers_plugin_data() {

				return get_plugin_data( __FILE__ );
			}

			function init() {

				if ( (! empty( $_GET['page'] )) && ($_GET['page'] == 'so-about') && (!empty($_GET['action'])) && ($_GET['action'] == 'import') ) {
					$args = $this->get_sample_offers();					
					$this->import_smart_offers( $args );
					update_option( 'smart_offers_sample_data_imported', 'yes' );
					wp_redirect( admin_url( 'edit.php?post_type=smart_offers' ) );
					exit;
				}
			}

			/**
			 * Language loader
			 */
			function localize() {

				$text_domains = array( SO_TEXT_DOMAIN, 'smart_offers' );		// For Backward Compatibility

				$plugin_dirname = SO_PLUGIN_DIRNAME;

				foreach ( $text_domains as $text_domain ) {

					self::$text_domain = $text_domain;

					$locale = apply_filters( 'plugin_locale', get_locale(), self::$text_domain );

					$loaded = load_textdomain( self::$text_domain, WP_LANG_DIR . '/' . $plugin_dirname . '/' . self::$text_domain . '-' . $locale . '.mo' );

					if ( ! $loaded ) {
						$loaded = load_plugin_textdomain( self::$text_domain, false, $plugin_dirname . '/languages' );
					}

					if ( $loaded ) {
						break;
					}

				}

			}

			/*
			 * Initializing So Upgrade class
			 */
			function initialize_so_upgrade() {
				$sku = 'so';
				$prefix = 'smart_offers';
				$plugin_name = 'Smart Offers';
				$documentation_link = 'http://www.storeapps.org/support/documentation/smart-offers/';
				new Store_Apps_Upgrade(__FILE__, $sku, $prefix, $plugin_name, self::$text_domain, $documentation_link);
			}
			
		}// End of class SA_Smart_Offers
		
	} // End class exists check

	/*
	 * Initializing SO class
	 */
	function initialize_so() {
		$GLOBALS['sa_smart_offers'] = new SA_Smart_Offers();
	}

	add_action('woocommerce_loaded', 'initialize_so');

}

register_activation_hook( __FILE__, array( 'SA_Smart_Offers', 'so_activate' ) );