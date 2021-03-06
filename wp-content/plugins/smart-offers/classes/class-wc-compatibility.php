<?php
if ( !defined( 'ABSPATH' ) ) exit;
if ( !class_exists( 'SA_WC_Compatibility' ) ) {

/**
 * Class to check for WooCommerce version & return variables accordingly
 * 
 * @version 1.2.0
 * @since 1.0.0 14-Feb-2014
 *
 */
	class SA_WC_Compatibility {

		/**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function global_wc() {
			if ( self::is_wc_21() ) {
				return WC();
			} else {
				global $woocommerce;
				return $woocommerce;
			}
		}

		/**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_get_formatted_name( $product = false ) {
			if ( self::is_wc_21() ) {
				return $product->get_formatted_name();
			} else {
				return woocommerce_get_formatted_product_name( $product );
			}
		}

		/**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_get_template( $template_path ) {

			if ( self::is_wc_21() ) {
				return wc_get_template( $template_path );
			} else {
				return woocommerce_get_template( $template_path );
			}
		}

		/**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_get_coupon_types() {

			if ( self::is_wc_21() ) {
				return wc_get_coupon_types();
			} else {
				global $woocommerce;
				return $woocommerce->get_coupon_discount_types();
			}
		}

		/**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_add_notice( $message, $notice_type = 'success' ) {

			if ( self::is_wc_21() ) {
				wc_add_notice( $message, $notice_type );
			} else {
				global $woocommerce;

				if ( 'error' == $notice_type ) {
					$woocommerce->add_error( $message );
				} else {
					$woocommerce->add_message( $message );
				}
			}
		}

		/**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_notice_count( $notice_type = '' ) {

			if ( self::is_wc_21() ) {
				return wc_notice_count( $notice_type );
			} else {
				global $woocommerce;

				if ( 'error' == $notice_type ) {
					return $woocommerce->error_count();
				} else {
					return $woocommerce->message_count();
				}
			}
		}
        
        /**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function get_checkout_pay_page_order_id() {

			if (self::is_wc_21()) {
				global $wp;
				return isset($wp->query_vars['order-received']) ? absint($wp->query_vars['order-received']) : 0;
			} else {
				return isset($_GET['order']) ? absint($_GET['order']) : 0;
			}
		}
                
        /**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_format_decimal($number, $dp = false, $trim_zeros = false) {

			if (self::is_wc_21()) {
				return wc_format_decimal($number, get_option( 'woocommerce_price_num_decimals' ), $trim_zeros);
			} else {
				return woocommerce_format_total($number);
			}
		}
                
        /**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function wc_price($price) {
			if (self::is_wc_21()) {
				return wc_price($price);
			} else {
				return woocommerce_price($price);
			}
		}
                
        /**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function wc_attribute_label($label) {

			if (self::is_wc_21()) {
				return wc_attribute_label($label);
			} else {
				global $woocommerce;
				return $woocommerce->attribute_label($label);
			}
		}
                
        /**
		 * @since 1.1.0 of SA_WC_Compatibility
		 */
		public static function wc_attribute_orderby($label) {

			if (self::is_wc_21()) {
				return wc_attribute_orderby($label);
			} else {
				global $woocommerce;
				return $woocommerce->attribute_orderby($label);
			}
		}

		/**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function is_wc_21() {
			return self::is_wc_greater_than( '2.0.20' );
		}

		/**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function get_wc_version() {
			if (defined('WC_VERSION') && WC_VERSION)
				return WC_VERSION;
			if (defined('WOOCOMMERCE_VERSION') && WOOCOMMERCE_VERSION)
				return WOOCOMMERCE_VERSION;
			return null;
		}

		/**
		 * @since 1.0.0 of SA_WC_Compatibility
		 */
		public static function is_wc_greater_than( $version ) {
			return version_compare( self::get_wc_version(), $version, '>' );
		}

	}

}