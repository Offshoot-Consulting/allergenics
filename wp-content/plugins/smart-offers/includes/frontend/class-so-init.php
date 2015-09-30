<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Init')) {

    Class SO_Init {

        function __construct() {
            ob_start();

            add_action('wp_head', array(&$this, 'so_process_offer_action'));
            add_action('wp_head', array(&$this, 'so_wp_head'));

            add_action('woocommerce_cart_updated', array(&$this, 'remove_offered_product_having_parent'), 10);
            add_action('woocommerce_before_cart_item_quantity_zero', array(&$this, 'remove_offered_product'), 10, 2);
            add_action('woocommerce_after_cart_item_quantity_update', array(&$this, 'remove_offered_product'), 10, 2);
            add_action('woocommerce_before_calculate_totals', array(&$this, 'add_offered_price'));
            add_action('woocommerce_checkout_process', array(&$this, 'add_offered_price_during_checkout'));
            add_action('woocommerce_checkout_update_order_meta', array(&$this, 'so_update_order_meta'), 10, 2);
            add_action('woocommerce_order_status_changed', array($this, 'change_paid_through_count'), 10, 3);
            add_action('wp_enqueue_scripts', array($this, 'apply_css_on_accept_skip_class'));
            add_action('wp_logout', array(&$this, 'so_clear_session'));
            add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'get_offered_cart_item_from_session'), 10, 3);
            add_filter('woocommerce_cart_item_quantity', array(&$this, 'offered_prod_cart_item_quantity'), 10, 2);
            add_action('woocommerce_before_checkout_process', array(&$this, 'remove_offered_product_having_parent'), 10);

            // Post-checkout
            add_action( 'woocommerce_after_checkout_form', array(&$this, 'smart_offers_post_checkout') );

            add_action( 'wp_ajax_parse_checkout_form_data', array(&$this, 'parse_checkout_form_data') );
            add_action( 'wp_ajax_nopriv_parse_checkout_form_data', array(&$this, 'parse_checkout_form_data') );

        }

        /**
    	* Enqueqe Accept/Skip CSS on preview offer
    	*/
        function so_wp_head() {
			global $sa_smart_offers;

            if (isset($_GET ['preview']) && $_GET['preview'] == 'true' && (!empty($_GET['preview_id']) ) ) {
                wp_enqueue_style('so_frontend_css');

                $offer_id = $_GET['preview_id'];

                $so_offer = new SO_Offer();

                $offer_content = $so_offer->return_post_content($offer_id, $page = '', $where_url = '');
               
                $show_offer_as = get_post_meta($offer_id, 'so_show_offer_as', true);

                if ($show_offer_as == "offer_as_inline") {

                    $js = "jQuery(document).ready(function() {

                                jQuery('div.site-content').find('div.entry-content').append('<div id=\"so_preview_inline\"></div>');
                                jQuery('#so_preview_inline').html('". html_entity_decode(esc_js( apply_filters('the_content', $offer_content) ) ) . "');
                                jQuery('#so-offer-content-" . $offer_id . "').css( 'display' , 'inline' );
                            });";

                } elseif ($show_offer_as == "offer_as_popup") {

                    if (!wp_script_is('jquery')) {
                        wp_enqueue_script('jquery');
                        wp_enqueue_style('jquery');
                    }

                    if (!wp_script_is('so_magnific_popup_js')) {
                        wp_enqueue_script ( 'so_magnific_popup_js', plugins_url('smart-offers/assets/js/jquery.magnific-popup.js'));
                    }

                    if (!wp_style_is('so_magnific_popup_css')) {
                        wp_enqueue_style ( 'so_magnific_popup_css', plugins_url('smart-offers/assets/css/magnific-popup.css'));
                    }
                    
                    echo apply_filters('the_content', $offer_content);

                    $js = "jQuery(document).ready(function() {

                                jQuery('#so-offer-content-". $offer_id . "').addClass('white-popup');
                                                            
                                //magnificPopup
                                
                                jQuery.magnificPopup.open({
                                        items: {
                                                  src: jQuery('#so-offer-content-" . $offer_id . "')
                                                },
                                            type: 'inline',
                                            modal: true,
                                            tError: '". __('The content could not be loaded.' , SA_Smart_Offers::$text_domain) . "'
                                 });
                        });";
                }

                $sa_smart_offers->enqueue_js($js);

            }
        }

        /**
    	 * Add Accept/Skip CSS
    	 */
        function apply_css_on_accept_skip_class() {
            wp_register_style('so_frontend_css', plugins_url(SMART_OFFERS) . '/assets/css/frontend.css', 'so_frontend_css');

            $button_style = get_option('so_accept_button_styles');

            if ( $button_style == 'smart_offers_custom_style_button' ) {
                $accept = get_option('so_css_for_accept');
            } else {
                $accept = get_option( $button_style );
            }

            $skip = get_option('so_css_for_skip');
            $style_for_accept = "div.so_accept { $accept }";
            $style_for_skip = "div.so_skip { $skip }";
            wp_add_inline_style('so_frontend_css', $style_for_accept);
            wp_add_inline_style('so_frontend_css', $style_for_skip);
            if ( $button_style != 'smart_offers_custom_style_button' ) {
                $style_for_accept_text = "div.so_accept a { text-decoration: none !important; color: white; }";
                wp_add_inline_style('so_frontend_css', $style_for_accept_text);
            }
        }
        
        /**
    	 * Remove upsell product from cart if cart contains rule does not satisfy
    	 */
        function remove_offered_product($cart_item_key, $quantity = 0) {
            global $sa_smart_offers;

            // To execute the function only if some product is being removed or quantity set to 0
            if ($quantity == 0) {

                $cart = $sa_smart_offers->global_wc()->cart->cart_contents;
                unset($cart[$cart_item_key]);

                $count_of_offered_prod_in_cart = 0;
                $count_of_non_offered_prod_in_cart = 0;
                $count_offered_product_having_parent_id = 0;
                $key_of_offered_prod_having_parent_id = array();

                foreach ($cart as $key => $values) {

                    if (isset($values['smart_offers']['cart_contains_keys'])) {
                        $count_of_offered_prod_in_cart++;
                    } else {
                        $count_of_non_offered_prod_in_cart++;
                    }
                }

                $offer_ids_to_unset = array();

                // To perform further execution only of there are offered prod in cart
                if ($count_of_offered_prod_in_cart > 0) {

                    foreach ($cart as $key => $values) {

                        if (isset($values['smart_offers']) && isset($values['smart_offers']['cart_contains_keys'])) {
                            $cart_contains_keys = $values['smart_offers']['cart_contains_keys'];

                            foreach ($cart_contains_keys as $k => $cart_key) {

                                if ($cart_item_key == $cart_key) {

                                    if (isset($values['smart_offers']['parent_offer_id'])) {
                                        unset($sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['cart_contains_keys'][$k]);
                                    }

                                    unset($cart[$key]['smart_offers']['cart_contains_keys'][$k]);
                                }
                            }
                        }
                    }

                    $cart_items_keys_to_be_removed = array();

                    foreach ($cart as $k => $v) {

                        if (isset($v['smart_offers']) && isset($v['smart_offers']['cart_contains_keys'])) {

                            $cart_contains_keys = $v['smart_offers']['cart_contains_keys'];
                            $cart_contains_ids = $v['smart_offers']['cart_contains_ids'];
                            $ids = array();

                            if (!empty($cart_contains_keys)) {

                                foreach ($cart_contains_keys as $cart_contains_key) {

                                    if ($cart[$cart_contains_key]['variation_id'] != '') {
                                        $ids[] = $cart[$cart_contains_key]['variation_id'];
                                        $ids[] = $cart[$cart_contains_key]['product_id'];
                                    } else {
                                        $ids[] = $cart[$cart_contains_key]['product_id'];
                                    }
                                }
                            } else {

                                foreach ($cart as $item_key => $item_val) {

                                    if ($k != $item_key) {
                                        if ($cart[$item_key]['variation_id'] != '') {
                                            $ids[] = $cart[$item_key]['variation_id'];
                                            $ids[] = $cart[$item_key]['product_id'];
                                        } else {
                                            $ids[] = $cart[$item_key]['product_id'];
                                        }
                                    }
                                }
                            }

                            $cart_contains_value = ( count(array_intersect($cart_contains_ids, $ids)) == count($cart_contains_ids) ) ? 1 : 0;

                            if ($cart_contains_value == 0) {

                                if (isset($v['smart_offers']) && isset($v['smart_offers']['parent_offer_id'])) {
                                    continue;
                                } else {
                                    unset($cart[$k]);
                                    $cart_items_keys_to_be_removed[] = $k;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            continue;
                        }
                    }
                    if (!empty($cart_items_keys_to_be_removed)) {
                        foreach ($cart_items_keys_to_be_removed as $item_key) {

                            $offer_id = $sa_smart_offers->global_wc()->cart->cart_contents[$item_key]['smart_offers']['offer_id'];
                            $offer_ids_to_unset[] = $offer_id;
                            if (isset($sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['parent_offer_id'])) {
                                $offer_ids_to_unset[] = $sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['parent_offer_id'];
                            }
                            unset($sa_smart_offers->global_wc()->cart->cart_contents[$item_key]);
                        }
                    } else {
                        return;
                    }

                    if (count($offer_ids_to_unset) > 0) {
                        SO_Session_Handler::unset_offer_ids_from_session($offer_ids_to_unset);
                    }
                } else {
                    return;
                }
            } else {
                return;
            }
        }

        /**
    	 * Remove upsell product from cart if rules of upsell offer or it's parent offer don't satisfy
    	 */
        function remove_offered_product_having_parent() {
            global $sa_smart_offers;
             
            if ( empty( $sa_smart_offers->global_wc()->cart->cart_contents ) ) return;
           
            $num = did_action( 'woocommerce_cart_updated' );
            if ( $num > 1 ) return;

            $hook_name = current_filter();           
            $so_offers = new SO_Offers();
            $offer_ids_to_unset = array();

            foreach ( $sa_smart_offers->global_wc()->cart->cart_contents as $key => $values ) {
               
                $offer_ids = array();
                if ( isset( $values['smart_offers'] ) ) {

                    // To validate the offers on any updation of cart
                    if (( $values['smart_offers']['accepted_from'] == "cart_page" || $values['smart_offers']['accepted_from'] == "checkout_page" || $values['smart_offers']['accepted_from'] == "myaccount_page" || $values['smart_offers']['accepted_from'] == "home_page" || $values['smart_offers']['accepted_from'] == "any_page")) {

                        $offer_ids[] = $values['smart_offers']['offer_id'];

                        if ( ( isset( $values['smart_offers']['cart_contains_keys'] ) && empty( $values['smart_offers']['cart_contains_keys'] ) ) ) {
                            if (is_array($values['smart_offers']['parent_offer_id'])) {
                                $offer_ids = array_unique(array_merge($offer_ids, $values['smart_offers']['parent_offer_id']));
                            } else {
                                $offer_ids[] = $values['smart_offers']['parent_offer_id'];
                            }
                        }
                    }

                    if ( !empty($offer_ids) ) {
                       
                        $valid_offers = $this->is_offer_valid( "cart_page", $offer_ids );
                       
                        if ( empty($valid_offers) ) {
                            $offer_id = $sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['offer_id'];
                            $offer_ids_to_unset[] = $offer_id;
                            if ( isset( $sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['parent_offer_id'] ) ) {
                                $offer_ids_to_unset[] = $sa_smart_offers->global_wc()->cart->cart_contents[$key]['smart_offers']['parent_offer_id'];
                            }

                            if ( !empty( $values['variation_id'] ) ) {
                                $product = $sa_smart_offers->get_product( $values['variation_id'] );
                                $removed_product_name = $sa_smart_offers->wc_get_formatted_name( $product );
                            } else {
                                $removed_product_name = get_the_title( $values['product_id'] );
                            }

                            $sa_smart_offers->global_wc()->cart->set_quantity($key, 0);
                            
                            // Display notice
                            
                            if ( $hook_name == 'woocommerce_before_checkout_process' ) {
                                
                                $sa_smart_offers->global_wc()->session->set( 'refresh_totals', true );
                            }

                            if ( ! array_key_exists( 'woocommerce_checkout_update_totals', $_POST ) ) {
                                $_POST['woocommerce_checkout_update_totals'] = '';
                            } 

                            $sa_smart_offers->wc_add_notice( sprintf( __( 'Product %s is removed, because it is not valid for your cart.', SA_Smart_Offers::$text_domain ), $removed_product_name ), 'error' );
                           
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }

            if (count($offer_ids_to_unset) > 0) {
                SO_Session_Handler::unset_offer_ids_from_session($offer_ids_to_unset);
            }
                      
        }

        /**
    	 * Add meta information in the order and increase the count of offer
    	 */
        function so_update_order_meta($order_id, $posted) {
            global $sa_smart_offers;
            
            $so_order_meta = get_post_meta($order_id, 'smart_offers_meta_data', true);

            foreach ($sa_smart_offers->global_wc()->cart->get_cart() as $cart_key => $values) {

                if (isset($values ['smart_offers'])) {

                    $offer_id = $values ['smart_offers'] ['offer_id'];
                    $so_order_count = get_post_meta($offer_id, 'so_order_count', true);
                    $count = (empty($so_order_count) || !array_key_exists('order_count', $so_order_count)) ? 1 : ++$so_order_count ['order_count'];
                    $so_order_count ['order_count'] = $count;
                    update_post_meta($offer_id, 'so_order_count', $so_order_count);
                    $product_id = (!empty($values ['variation_id'])) ? $values ['variation_id'] : $values ['product_id'];

                    if ( empty( $so_order_meta ) && ! is_array( $so_order_meta ) ) {
                        $so_order_meta = array();
                    }

                    if ( ! empty( $so_order_meta ) && ! is_array( $so_order_meta ) ) {
                        $so_order_meta = array( $offer_id => $so_order_meta );
                    }

                    $so_order_meta [$offer_id] ['product_id'] = $product_id;
                    $so_order_meta [$offer_id] ['offered_price'] = $values ['data']->price;
                }
            }

            if (!empty($so_order_meta)) {
                update_post_meta($order_id, 'smart_offers_meta_data', $so_order_meta);
            }
        }

        /**
    	 * Fetch all skipped offers of cart and account page by user
    	 */
        function get_skipped_offers($current_offer_id) {
            global $current_user;

            $user_skipped_offers = get_user_meta($current_user->ID, 'customer_skipped_offers', true);
            
            if (!empty($user_skipped_offers)) {
                $customer_skipped_offers = maybe_unserialize($user_skipped_offers);
            }
            $customer_skipped_offers [] = $current_offer_id;
            $customer_skipped_offers = array_unique($customer_skipped_offers); 

            return $customer_skipped_offers;
        }

        /**
    	* Add offered price in cart.
    	*/
        function add_offered_price($cart_object) {

            $so_offer = new SO_Offer();
            if (sizeof($cart_object->cart_contents) > 0) {

                foreach ($cart_object->cart_contents as $key => $value) {
                    if (isset($value ['smart_offers'] ['accept_offer'])) {

                        $product_id = ( isset($value['variation_id']) && $value['variation_id'] != '' ) ? $value['variation_id'] : $value['product_id'];
                        $offer_id = $value ['smart_offers'] ['offer_id'];
                        $price = $so_offer->get_offer_price(array('offer_id' => $offer_id, 'prod_id' => $product_id));
                        $value ['data']->price = $price;
                        $value ['data']->sale_price = $price;
                        $value ['data']->regular_price = $price;
                    }
                }
            }
        }

        /**
    	* Add offered price in checkout.
    	*/
        function add_offered_price_during_checkout() {
            global $sa_smart_offers;

            $cart = $sa_smart_offers->global_wc()->cart->cart_contents;

            if (sizeof($cart) > 0) {
                foreach ($cart as $key => $value) {

                    if (isset($value ['smart_offers']['accept_offer'])) {

                        $product_id = ( isset($value['variation_id']) && $value['variation_id'] != '' ) ? $value['variation_id'] : $value['product_id'];
                        $offer_id = $value ['smart_offers'] ['offer_id'];
                        $so_offer = new SO_Offer();
                        $price = $so_offer->get_offer_price(array('offer_id' => $offer_id, 'prod_id' => $product_id));
                        $value ['data']->price = $price;
                        $value ['data']->sale_price = $price;
                        $value ['data']->regular_price = $price;
                    }
                }
            }
        }

        /**
    	* Set quantity for the offered product in cart.
    	*/
        function offered_prod_cart_item_quantity($quantity, $cart_item_key) {
            global $sa_smart_offers;

            if (isset($sa_smart_offers->global_wc()->cart->cart_contents [$cart_item_key] ['smart_offers']))
                return $sa_smart_offers->global_wc()->cart->cart_contents [$cart_item_key] ['quantity'];
            return $quantity;
        }

        /**
        * Function to show offer just after Place Order button is clicked on checkout page, if a valid offer is available
        */
        function smart_offers_post_checkout() {
            global $sa_smart_offers;

            $so_offer = new SO_Offer();
            $so_offers = new SO_Offers();

            $page = 'post_checkout_page';
            list($where, $where_url) = $so_offers->get_page_details();

            $so_get_offers = $so_offers->get_offers($offer_ids = null);
            $so_offers_id = $so_offers->get_valid_offer_ids($so_get_offers);            //$offer_id = $so_get_offers['offer_data'][0]['post_id'];
            if( !empty ( $so_offers_id ) ) {
                foreach( $so_offers_id as $key => $value ) {
                    $offer_id = $key;

                    $offer_rule_page_options = get_post_meta( $offer_id, 'offer_rule_page_options', true );
                    $offer_rule_pages = explode( ',', $offer_rule_page_options );

                    if ( ! in_array( $page, $offer_rule_pages ) ) {
                        continue;
                    } else {
                        $show_offer_as = get_post_meta($offer_id, 'so_show_offer_as', true);
                        if ($show_offer_as == "offer_as_popup") {

                            if ( ! wp_script_is( 'jquery' ) ) {
                                wp_enqueue_script( 'jquery' );
                                wp_enqueue_style( 'jquery' );
                            }

                            if ( ! wp_script_is( 'so_magnific_popup_js' ) ) {
                                wp_enqueue_script( 'so_magnific_popup_js', trailingslashit( plugins_url() ) . dirname( plugin_basename( SO_PLUGIN_FILE ) ) . '/assets/js/jquery.magnific-popup.js' );
                            }

                            if ( ! wp_style_is( 'so_magnific_popup_css' ) ) {
                                wp_enqueue_style( 'so_magnific_popup_css', trailingslashit( plugins_url() ) . dirname( plugin_basename( SO_PLUGIN_FILE ) ) . '/assets/css/magnific-popup.css' );
                                wp_enqueue_style( 'so_frontend_css' );
                            }
                        }
                        ?>
                        <div class="smart-offers-post-action" id="smart-offers-post-action">
                            <?php
                                $sa_so_offer_content = $so_offer->return_post_content($offer_id, $page, $where_url);
                                $processed_offer_content = apply_filters('the_content', $sa_so_offer_content);
                                echo $processed_offer_content;
                            ?>
                        </div>
                        <?php

                            $js = "jQuery(document).ready(function() {
                                        var post_element = jQuery( 'div#smart-offers-post-action' );
                                        if( ( post_element.length ) > 0 ) {
                                            if( ( post_element.find( 'div.so-offer-content' ).length ) > 0 ){
                                                post_element.find( 'div.so-offer-content' ).hide();
                                            }
                                        }
                                    });

                                jQuery('body').on( 'click', 'input#place_order.button.alt', function( e ) {
                                    var post_element = jQuery( 'div#smart-offers-post-action' );
                                    if( ( post_element.length ) > 0 ) {
                                        e.preventDefault();
                                        var checkout_form_data = jQuery('form.checkout').serialize();
                                        jQuery.ajax({
                                            url: '" . admin_url( 'admin-ajax.php' ) . "',
                                            type: 'POST',
                                            dataType: 'json',
                                            data: {
                                                action: 'parse_checkout_form_data',
                                                security: '" . wp_create_nonce( 'post_checkout_offers' ) . "',
                                                form_data: checkout_form_data
                                            },
                                            success: function( response ) {
                                                post_element.show();
                                                if ( response.success != '' && response.success != undefined && response.success == 'no' ) {
                                                    console.log( '" . __( 'Unable to save checkout form data.', SA_Smart_Offers::$text_domain ) . "' );
                                                }
                                            }
                                        });
                                        if( ( post_element.find( 'div.so-offer-content.so-inline' ).length ) > 0 ) {
                                            jQuery( 'form.checkout' ).hide();
                                            post_element.find( 'div.so-offer-content' ).show();
                                        } else if( ( post_element.find( 'div.so-offer-content.so-popup' ).length ) > 0 ) {
                                            post_element.find( 'div.so-offer-content' ).addClass( 'white-popup' );
                                            jQuery.magnificPopup.open({
                                                items: {
                                                          src: jQuery( 'div#smart-offers-post-action div.so-offer-content' )
                                                        },
                                                type: 'inline',
                                                modal: true,
                                                tError: '". __( "The content could not be loaded." , SA_Smart_Offers::$text_domain ) . "'
                                            });
                                            post_element.find( 'div.so-offer-content.so-popup' ).show();
                                        }
                                    }
                                });
                            ";

                        $sa_smart_offers->enqueue_js($js);

                        // After accepting an offer, 'offer_shown' was counted additionally, hence skipping it
                        if ( ! ( ! empty( $_REQUEST['so_action'] ) && $_REQUEST['so_action'] == 'accept' ) ) {
                            $so_offer->update_accept_skip_count($offer_id, 'offer_shown');
                        }
                    }
                }
            }
        }

        /**
        * Function to save checkout form data used to process after checkout
        */
        function parse_checkout_form_data() {

            check_ajax_referer( 'post_checkout_offers', 'security' );

            $checkout_form_data = ( ! empty( $_POST['form_data'] ) ) ? $_POST['form_data'] : '';

            $response = array();
            
            if ( empty( $checkout_form_data ) ) {
                $response['success'] = 'no';
            } else {
                parse_str( $checkout_form_data, $simplified_form_data );
                SO_Session_Handler::so_set_session_variables( 'so_checkout_form_data', $simplified_form_data );
                $response['success'] = 'yes';
            }

            echo json_encode( $response );
            die();
        }

        /**
        * Add offered price in cart.
        */
        function get_offered_cart_item_from_session($cart_item, $values, $key = null) {

            if (isset($values ['smart_offers'])) {
                $so_offer = new SO_Offer();
                $cart_item ['smart_offers'] = $values ['smart_offers'];

                $product_id = ( isset($cart_item['variation_id']) && $cart_item['variation_id'] != '' ) ? $cart_item['variation_id'] : $cart_item['product_id'];
                $offer_id = $values ['smart_offers'] ['offer_id'];
                $price = $so_offer->get_offer_price(array('offer_id' => $offer_id, 'prod_id' => $product_id));
                $cart_item ['data']->price = $price;
                $cart_item ['data']->sale_price = $price;
                $cart_item ['data']->regular_price = $price;
            }

            return $cart_item;
        }
        
        /**
    	* Action to perform on accept/skip offer
    	*/
        function so_process_offer_action() {
            global $current_user, $sa_smart_offers;
            
            $so_offer = new SO_Offer();
            $so_offers = new SO_Offers();

            if (isset($_GET['so_action']) && ( $_GET['so_action'] == "accept" || $_GET ['so_action'] == "skip" )) {

                $current_offer_id = $_GET['so_offer_id'];

                $source = ( ! empty( $_GET['source'] ) ) ? $_GET['source'] : null;

                list($where, $where_url) = $so_offers->get_page_details();
                $page = $where . '_page';

                list($accepted_session_variable, $accepted_ids_in_session) = $so_offers->get_accepted_offer_ids_from_session();
                list($skipped_session_variable, $skipped_ids_in_session) = $so_offers->get_skipped_offer_ids_from_session();

                $skip_offer_id_variable = ( $where == "any" ) ? str_replace(array('/', '-', '&', '=', ':'), '', $where_url) . '_skip_offer_id' : $where . '_skip_offer_id';
                list($offer_id_on_skipping, $skipped_offer_id_variable) = $so_offers->get_offer_id_on_skipping($skip_offer_id_variable);
                
                $parent_offer_id_variable = ( $where == "any" ) ? str_replace(array('/', '-', '&', '=', ':'), '', $where_url) . '_parent_offer_id' : $where . '_parent_offer_id';
                $check_parent_offer_id_set_or_not = SO_Session_Handler::check_session_set_or_not($parent_offer_id_variable);

                if (!$check_parent_offer_id_set_or_not) {
                    SO_Session_Handler::so_set_session_variables($parent_offer_id_variable, $current_offer_id);
                }
                if ($_GET['so_action'] == "accept") {

                    $variation_data = ( isset($_POST['variation_id']) || isset($_POST['quantity']) ) ? $_POST : array();
                    $parent_offer_id = '';

                    if ($offer_id_on_skipping != '') {
                        $check_parent_offer_id = SO_Session_Handler::check_session_set_or_not($parent_offer_id_variable);
                        $parent_offer_id = ( $check_parent_offer_id ) ? SO_Session_Handler::so_get_session_value($parent_offer_id_variable) : '';
                    }

                    SO_Session_Handler::so_delete_session($parent_offer_id_variable);
                    SO_Session_Handler::so_delete_session($skip_offer_id_variable);

                    SO_Session_Handler::so_set_session_variables('sa_smart_offers_accepted_offer_ids', $current_offer_id);

                    // Update stats
                    $so_offer->update_accept_skip_count($current_offer_id, 'accepted');
                   
                    // validate offer before add to cart.
                    $offer_ids = array($current_offer_id);
                    $is_valid = $this->is_offer_valid( $page, $offer_ids );
                   
                    if ( !empty( $is_valid ) && is_array( $is_valid ) ) {
                        // Adds to cart
                        $so_offer->action_on_accept_offer($current_offer_id, $page, $parent_offer_id, $variation_data);
                    } else {
                        // display notice
                        $sa_smart_offers->wc_add_notice( __( 'This is not valid offer for you.', SA_Smart_Offers::$text_domain ), 'error' );
                    }
                
                } elseif ($_GET['so_action'] == "skip") {

                    $so_offer->update_accept_skip_count($current_offer_id, 'skipped');

                    // Update if this offer needs to be skipped permanently for this user
                    $skip_permanently = get_post_meta($current_offer_id, 'sa_smart_offer_if_denied_skip_permanently', true);

                    if (!empty($skip_permanently) && $skip_permanently == true && $current_user->ID != 0) {
                        $customer_skipped_offers = $this->get_skipped_offers($current_offer_id);
                        $customer_skipped_offers = array_unique($customer_skipped_offers);
                        update_user_meta($current_user->ID, 'customer_skipped_offers', $customer_skipped_offers );
                    }

                    // To store skipped offers in session even if they are updated in DB
                    SO_Session_Handler::so_set_session_variables('sa_smart_offers_skipped_offer_ids', $current_offer_id);
                    SO_Session_Handler::so_delete_session($skip_offer_id_variable);

                    $redirecting_option = get_post_meta($current_offer_id, 'sa_smart_offer_if_denied', true);
                    $redirect_to = get_post_meta($current_offer_id, 'url', true);

                    if ( strpos( $where_url, 'so_action=skip' ) ) {
                        $where_url = esc_url( remove_query_arg( array('so_action', 'so_offer_id', 'source'), $where_url ) );
                    }

                    ob_clean();

                    if (empty($redirecting_option)) {
                        wp_safe_redirect($where_url);
                    } elseif ( ! empty( $source ) ) {

                        if ( $page == "checkout_page" && $source == 'so_post_checkout' ) {
                            
                            $form_values = SO_Session_Handler::check_session_set_or_not('so_checkout_form_data');

                            if ( $form_values ) {
                                $sa_so_form_checkout = SO_Session_Handler::so_get_session_value('so_checkout_form_data');
                            } else {
                                $sa_so_form_checkout = null;
                            }
                            
                            if ( ! empty( $sa_so_form_checkout ) ) {
                                $_POST = $sa_so_form_checkout;
                            }

                            if ( wc_get_page_id( 'terms' ) > 0 ) {
                                $_POST['terms'] = 'yes';
                            }

                            if ( $sa_smart_offers->is_wc_gte_21() ) {
                                wc_clear_notices();
                            } else {
                                $sa_smart_offers->global_wc()->clear_messages();
                            }

                            $woocommerce_checkout = $sa_smart_offers->global_wc()->checkout();
                            $woocommerce_checkout->process_checkout();
                            
                        } elseif ( $page == "checkout_page" && $source == 'so_pre_checkout' ) {
                            wp_safe_redirect($where_url);
                        }
                    } else {
                        if ($redirecting_option == 'order_page') {
                            wp_safe_redirect($where_url);
                        } elseif ($redirect_to != "") {

                            if ($redirecting_option == "offer_page") {
                                $so_offer->force_show_smart_offers($redirect_to);
                            } elseif ($redirecting_option == "url") {

                                if (!preg_match("~^(?:ht)tps?://~i", $redirect_to)) {
                                    $return_url = (@$_SERVER ["HTTPS"] == "on") ? "https://" : "http://";
                                    $return_url = "http://" . $redirect_to;
                                } else {
                                    $return_url = $redirect_to;
                                }

                                wp_redirect($return_url);
                            } elseif ($redirecting_option == "particular_page") {
                                wp_safe_redirect(get_permalink($redirect_to));
                            }
                        }
                    }
                    exit;
                }
            }
        }


        /*
        * validate offer before and after add to cart
        */
        function is_offer_valid($page, $offer_ids) {
           global $sa_smart_offers;
            
            $so_offers = new SO_Offers();

            //Get user's details
            $user_details = $so_offers->get_user_details( $page, '' );

            $dp = (int) get_option('woocommerce_price_num_decimals');
            $sa_smart_offers->global_wc()->cart->calculate_shipping();

            if ($sa_smart_offers->is_wc_gte_20()) {
                if ( $sa_smart_offers->is_wc_gte_23() ) {
                    $cart_total = apply_filters( 'woocommerce_calculated_total', round( $sa_smart_offers->global_wc()->cart->cart_contents_total + $sa_smart_offers->global_wc()->cart->tax_total + $sa_smart_offers->global_wc()->cart->shipping_tax_total + $sa_smart_offers->global_wc()->cart->shipping_total + $sa_smart_offers->global_wc()->cart->fee_total, $sa_smart_offers->global_wc()->cart->dp ), $sa_smart_offers->global_wc()->cart );
                } else {
                    $cart_total = apply_filters('woocommerce_calculated_total', number_format($sa_smart_offers->global_wc()->cart->cart_contents_total + $sa_smart_offers->global_wc()->cart->tax_total + $sa_smart_offers->global_wc()->cart->shipping_tax_total + $sa_smart_offers->global_wc()->cart->shipping_total - $sa_smart_offers->global_wc()->cart->discount_total + $sa_smart_offers->global_wc()->cart->fee_total, $dp, '.', ''), $sa_smart_offers->global_wc()->cart);
                }
            } else {
                $cart_total = apply_filters('woocommerce_calculated_total', number_format($sa_smart_offers->global_wc()->cart->cart_contents_total + $sa_smart_offers->global_wc()->cart->tax_total + $sa_smart_offers->global_wc()->cart->shipping_tax_total + $sa_smart_offers->global_wc()->cart->shipping_total - $sa_smart_offers->global_wc()->cart->discount_total, $dp, '.', ''), $sa_smart_offers->global_wc()->cart);
            }

            // Get Cart/Order details
            $cart_order_details = $so_offers->get_cart_contents();
            $cart_order_details['offer_rule_grand_total'] = $cart_total;
            $details = array_merge( $user_details, $cart_order_details );
            $offer_to_validate = $so_offers->get_all_offer_rules_meta( $offer_ids );
            $valid_offer_ids = $so_offers->validate_offers( $page, $offer_to_validate, $details );
            $key = ( !empty( $offer_ids[0] ) ) ? $offer_ids[0] : 0;
            if( !empty( $valid_offer_ids ) && array_key_exists( $key , $valid_offer_ids ) ) {
                return $valid_offer_ids;
            } else {
                return false;
            }
        }

        /**
    	 * Empty SO related session data on logout
    	 */
        function so_clear_session() {
            global $sa_smart_offers;

            SO_Session_Handler::so_delete_session('sa_smart_offers_skipped_offer_ids');
            SO_Session_Handler::so_delete_session('sa_smart_offers_accepted_offer_ids');

            $pages = array('cart', 'checkout', 'thankyou', 'myaccount', 'home', 'any');

            foreach ($pages as $page) {
                SO_Session_Handler::so_delete_session($page . '_skip_offer_id');
                SO_Session_Handler::so_delete_session($page . '_parent_offer_id');
            }

            if ($sa_smart_offers->is_wc_gte_21()) {
                $data = ( ! empty( $sa_smart_offers->global_wc()->session ) ) ? get_option('_wc_session_' . $sa_smart_offers->global_wc()->session->get_customer_id(), array()) : null;
            } else {
                $data = $_SESSION;
            }

            if (!empty($data)) {
                foreach ($data as $key_name => $value) {
                    if (strpos($key_name, '_skip_offer_id') !== false || strpos($key_name, '_parent_offer_id') !== false) {
                        SO_Session_Handler::so_delete_session($key_name);
                    }
                }
            }
        }

        /**
    	 * Change the order count in case of order status change
    	 */
        function change_paid_through_count($order_id, $old_status, $new_status) {

            // In WC 2.2 also, woocommerce_order_status_changed pass previous statuses, not new one, therefore no need of change
            $order_statuses = array( 'cancelled', 'refunded', 'failed' );

            if ( in_array( $new_status, $order_statuses, true ) && in_array( $old_status, $order_statuses, true ) ) {
                return;
            }

            $is_change_paid_through_count = false;

            if ( in_array( $new_status, $order_statuses, true ) ) {
                $is_change_paid_through_count = true;
            }

            if ( $is_change_paid_through_count ) {
                $so_order_meta = get_post_meta($order_id, 'smart_offers_meta_data', true);
                foreach ($so_order_meta as $offer_id => $offer_data) {
                    $order_count = get_post_meta($offer_id, 'so_order_count', true);
                    if ($order_count) {
                        $count = --$order_count['order_count'];
                        $order_count['order_count'] = $count;
                        update_post_meta($offer_id, 'so_order_count', $order_count);
                    }
                }
            }
        }

    }

}

return new SO_Init();