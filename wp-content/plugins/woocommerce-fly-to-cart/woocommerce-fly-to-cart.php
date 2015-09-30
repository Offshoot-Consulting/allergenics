<?php
/*
Plugin Name: WooCommerce Fly to cart effect + Ajax add to cart
Description: A plugin that gives your WooCommerce store an outstanding user experience.
Version: 1.1.0
Author: Karapet Abrahamyan
Author URI: http://oraksoftware.com/
*/

if (!defined('ABSPATH'))
    die();

class OrakAtcEffects
{
	public static $options;
	
	const OPTIONS_PAGE_NAME = 'orakatceffects_settings',
		  OPTION_GROUP = 'orak_vars_group',
		  OPTION_NAME = 'orak_vars',
		  
		  TEXTDOMAIN = 'orakeffs';

	public function Install()
	{
		OrakAtcEffects::$options = array(
						'use_default_selector'=> 1,
						'custom_selector' => null,
						'custom_selector_update' => null,
						'custom_selector_cb' => null,
						'use_default_gallery_image' => null,
						'gallery_image_selector' => null,
						'element_num' => 1,
						'arrive_offset_x' => 20,
						'arrive_offset_y' => 20,
						'flying_speed' => 1500,
						'flying_img_opacity' => 40,
						'use_blink_effect' => 1,
						'scroll_on_add' => 1,
						'use_woocommerce_widget' => 1,
						'use_product_grabber_2' => null,
						'update_level' => 2
					);
		add_option(OrakAtcEffects::OPTION_NAME, OrakAtcEffects::$options);
	}
	
	public function Uninstall()
	{
		delete_option(OrakAtcEffects::OPTION_NAME);
	}
	
	public function Init()
	{
		OrakAtcEffects::$options = get_option(OrakAtcEffects::OPTION_NAME);
		
		add_action('wp_ajax_orak_add_to_cart', array( get_called_class(), 'addToCart') );
		add_action('wp_ajax_nopriv_orak_add_to_cart', array( get_called_class(), 'addToCart') );
		add_action('wp_enqueue_scripts', array( get_called_class(), 'loadScripts'));
		add_action('admin_init', array( get_called_class(), 'registerSettingVars'));
		add_action('admin_menu', array( get_called_class(), 'registerMenusAndLinks'));
		
		register_activation_hook(__FILE__, array(get_called_class(), 'Install'));
		register_deactivation_hook(__FILE__, array(get_called_class(), 'Uninstall'));
		
	}
	
	public static function loadScripts()
	{
		add_action('wp_head', 
			function() {
				global $woocommerce;
				echo '<script>';
				foreach(OrakAtcEffects::$options as $varname => $option)
				{
					if($option == null) continue;
					
					echo 'var '.$varname.' = "'.$option.'";';
				}
				echo 'var site_url = "'.get_site_url().'";';
				echo 'var cart_url = "'.$woocommerce->cart->get_cart_url().'";';
				echo '</script>';
			}
		);
	
		add_action('wp_head', function() {
			wp_enqueue_script(
				'orakatc',
				plugins_url( 'orakatc.js' , __FILE__ ),
				array('jquery', 'jquery-effects-core', 'jquery-effects-pulsate')
			);
		});
	}
	
	public static function registerSettingVars()
	{
		register_setting(OrakAtcEffects::OPTION_GROUP, OrakAtcEffects::OPTION_NAME);
	}
	
	public static function registerMenusAndLinks()
	{
		add_options_page(__('OrakSoftware - WooCommerce "Fly" Effect Settings', OrakAtcEffects::TEXTDOMAIN), __('WooCommerce "Fly" Effect', OrakAtcEffects::TEXTDOMAIN), 'manage_options', OrakAtcEffects::OPTIONS_PAGE_NAME, array( get_called_class(), 'AdminPage'));
		
		add_filter("plugin_action_links_".plugin_basename(__FILE__), function($links) { 
			$settings_link = '<a href="options-general.php?page='.OrakAtcEffects::OPTIONS_PAGE_NAME.'">'.__('Settings', OrakAtcEffects::TEXTDOMAIN).'</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		} );
	}
	
	public static function AdminPage()
	{
		include 'templates/admin-settings.php';
	}
	
	public function addToCart()
	{
		global $woocommerce;

		$product_id = (int)$_POST['product_id'];
		$quantity = (int)$_POST['quantity'];
		
		$variation_id = (int)$_POST['variation_id'];
		
		if($variation_id <= 0) {
			$variation_id = null; $variation = null;
		} else {
			$variation = array_filter($_POST['attribute'], 'addslashes');
		}
		
		if( $woocommerce->cart ) {
			$woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation );
		}
		
		die();
	}
}


$ftc_plugin = new OrakAtcEffects();
$ftc_plugin->Init();
?>