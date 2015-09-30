<?php
	/*
	Plugin Name: WooCommerce Payment Express PX Fusion
	Plugin URI: http://allergenicstesting.devsite.co.nz
	Description: WooCommerce Implementation of the PXfusion Gateway method of Payment Express
	Author: Syed Ali Ahmad
	Author URI: http://www.syedaliahmad.com
	Version: 1.0.0

		Copyright: © 2015 Allergenics Corp (email : )
		License: GNU General Public License v3.0
		License URI: http://www.gnu.org/licenses/gpl-3.0.html
	*/

	/**
	 * Check if WooCommerce is active
	 */
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		// Include our Gateway Class and Register Payment Gateway with WooCommerce
	add_action( 'plugins_loaded', 'ali_payfusion_init', 0 );
	function ali_payfusion_init() {


		
		
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
		
		include_once( 'woocommerce-payfusion-gc.php' );
		
		
		// Now that we have successfully included our class,
		// Lets add it too WooCommerce
		add_filter( 'woocommerce_payment_gateways', 'ali_payfusion_gateway' );
		function ali_payfusion_gateway( $methods ) {
			$methods[] = 'Ali_PayFusion';
			return $methods;
		}
	}
	
	// Add custom action links
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ali_payfusion_action_links' );
	function ali_payfusion_action_links( $links ) {
		$plugin_links = array('<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' . __( 'Settings', 'ali_payfusion' ) . '</a>',	);

		// Merge our new link with the default ones
		return array_merge( $plugin_links, $links );	

	}
	
	
	
	
	
}
	
	
	
