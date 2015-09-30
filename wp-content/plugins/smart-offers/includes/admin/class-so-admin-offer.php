<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Offer')) {

    Class SO_Admin_Offer {

        function __construct() {
            add_action('add_meta_boxes', array(&$this, 'add_smart_offers_custom_box'));
            add_action('save_post', array(&$this, 'on_process_offers_meta'), 10, 2);

            add_action('wp_ajax_woocommerce_json_search_offers', array(&$this, 'woocommerce_json_search_offers'), 1, 2);
            add_action('wp_ajax_woocommerce_json_search_prod_category', array(&$this, 'woocommerce_json_search_prod_category'), 1, 2);
            add_action('wp_ajax_woocommerce_json_search_coupons', array(&$this, 'woocommerce_json_search_coupons'), 1, 2);
            add_action('wp_ajax_woocommerce_json_search_products_and_only_variations', array(&$this, 'woocommerce_json_search_products_and_only_variations'), 1, 2);
            add_action('admin_enqueue_scripts', array(&$this, 'so_admin_style'));

            add_filter('enter_title_here', array(&$this, 'woo_smart_offers_enter_title_here'), 1, 2);
            add_filter('default_content', array(&$this, 'so_add_default_content'));
            add_filter('post_updated_messages', array(&$this, 'so_add_custom_messages'));
            // To add product variation shortcode on save post
            add_filter('wp_insert_post_data', array(&$this, 'add_shortcode_in_post_content'));
        }

        /**
	 * Save meta data for Smart Offers
	 */
        function on_process_offers_meta($post_id, $post) {
            global $wpdb, $sa_smart_offers;

            if (empty($post_id) || empty($post) || empty($_POST))
                return;
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if (is_int(wp_is_post_revision($post)))
                return;
            if (is_int(wp_is_post_autosave($post)))
                return;
            if (empty($_POST ['woocommerce_meta_nonce']) || !wp_verify_nonce($_POST ['woocommerce_meta_nonce'], 'woocommerce_save_data'))
                return;
            if (!current_user_can('edit_post', $post_id))
                return;
            if ($post->post_type != 'smart_offers')
                return;

            $offer_rules = array(); // array to store data in serialized format
            // Delete product rules, but not the pages they need to be shown on...
            $delete_query = "DELETE FROM {$wpdb->prefix}postmeta where meta_key like 'offer_rule_%' and meta_key not like 'offer_rule_%_page' and meta_key != 'offer_rule_page_options' and post_id = $post_id ";
            $wpdb->query($delete_query);

            clean_post_cache( $post_id );

            if (isset($_POST ['offer_type'])) {

                $offer_type = $_POST ['offer_type'];
                $offer_action = $_POST ['offer_action'];
                $price = $_POST ['price'];

                $i = 0;
                foreach ($offer_type as $offer_key => $value) {

                    $offer_rules [$i] ['offer_type'] = $offer_type [$offer_key];

                    if ($offer_rules [$i] ['offer_type'] == "offer_valid_between") {

                        $offer_rules [$i] ['offer_action'] = $offer_rules [$i] ['offer_type'];

                        $offer_valid_from = $_POST["_offer_valid_from_" . $offer_key];
                        $offer_valid_till = $_POST["_offer_valid_till_" . $offer_key];

                        // Dates
                        if ($offer_valid_from) {
                            $date_from = strtotime($offer_valid_from);
                        } else {
                            $date_from = strtotime(date('Y-m-d'));
                        }

                        if ($offer_valid_till) {
                            $date_to = strtotime($offer_valid_till);
                        } else {
                            $date_to = '';
                        }

                        if ($offer_valid_till && !$offer_valid_from) {
                            $date_from = strtotime('NOW', current_time('timestamp'));
                        }

                        if ($offer_valid_till && strtotime($offer_valid_till) < strtotime('NOW', current_time('timestamp'))) {

                            $date_from = '';
                            $date_to = '';
                        }

                        $offer_valid_between = array();
                        $offer_valid_between['offer_valid_from'] = $date_from;
                        $offer_valid_between['offer_valid_till'] = $date_to;

                        $offer_rules [$i] ['offer_rule_value'] = $offer_valid_between;
                    } else {
                        $offer_rules [$i] ['offer_action'] = $offer_action [$offer_key];

                        if ($offer_action [$offer_key] == 'cart_total_less' || $offer_action [$offer_key] == 'cart_total_more' || $offer_action [$offer_key] == 'cart_grand_total_less' || $offer_action [$offer_key] == 'cart_grand_total_more' || $offer_action [$offer_key] == 'total_ordered_less' || $offer_action [$offer_key] == 'total_ordered_more') {

                            $offer_rules [$i] ['offer_rule_value'] = $price [$offer_key];
                        } elseif ($offer_action [$offer_key] == 'has_bought' || $offer_action [$offer_key] == 'not_bought' || $offer_action [$offer_key] == 'cart_doesnot_contains') {

                            $key = "search_product_ids_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = ( $sa_smart_offers->is_wc_gte_23() ) ? $_POST [$key] : implode(',', $_POST [$key]);
                        } elseif ($offer_action [$offer_key] == 'cart_contains') {

                            $key = "search_product_ids_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = ( $sa_smart_offers->is_wc_gte_23() ) ? $_POST [$key] : implode(',', $_POST [$key]);
                            $offer_rules [$i] ['quantity_total'] = ( ! empty( $_POST ['quantity_total_' . $i] ) ) ? $_POST ['quantity_total_' . $i] : '';
                            $offer_rules [$i] ['cart_quantity'] = ( ! empty( $_POST ['cart_quantity_' . $i] ) ) ? $_POST ['cart_quantity_' . $i] : '';

                        } elseif ($offer_action [$offer_key] == 'registered_user') {

                            $key = "registered_user_action_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = $_POST [$key];
                        } elseif ($offer_action [$offer_key] == 'registered_period') {

                            $key = "registered_period_action_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = $_POST [$key];
                        } elseif ($offer_action [$offer_key] == 'user_role') {

                            $key = "user_role_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = $_POST [$key];
                        } elseif ($offer_action [$offer_key] == 'user_role_not') {

                            $key = "user_role_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = $_POST [$key];
                        } elseif ($offer_action[$offer_key] == 'cart_prod_categories_is') {

                            $key = "search_category_ids_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = ( $sa_smart_offers->is_wc_gte_23() ) ? $_POST[ $key ] : implode(',', $_POST [$key]);
                            $offer_rules [$i] ['category_total'] = ( ! empty( $_POST ['category_total_' . $i] ) ) ? $_POST ['category_total_' . $i] : '';
                            $offer_rules [$i] ['category_amount'] = ( ! empty( $_POST ['category_amount_' . $i] ) ) ? $_POST ['category_amount_' . $i] : '';

                        } elseif ($offer_action[$offer_key] == 'cart_prod_categories_not_is') {

                            $key = "search_category_ids_" . $offer_key;
                            $offer_rules [$i] ['offer_rule_value'] = ( $sa_smart_offers->is_wc_gte_23() ) ? $_POST[ $key ] : implode(',', $_POST [$key]);
                            
                        }
                    }

                    $i++;
                }
            }

            foreach ($offer_rules as $j) {
                if (array_key_exists('offer_action', $j) && array_key_exists('offer_rule_value', $j)) {
                    $meta_key = 'offer_rule_' . $j ['offer_action'];
                    update_post_meta($post_id, $meta_key, $j ['offer_rule_value']);
                    if ($meta_key == "offer_rule_cart_prod_categories_is") {
                        update_post_meta($post_id, "offer_rule_category_total", $j ['category_total']);
                        update_post_meta($post_id, "offer_rule_category_amount", $j ['category_amount']);
                    } elseif ($meta_key == "offer_rule_cart_contains") {
                        update_post_meta($post_id, "offer_rule_quantity_total", $j ['quantity_total']);
                        update_post_meta($post_id, "offer_rule_cart_quantity", $j ['cart_quantity']);
                    }
                }
            }

            update_post_meta($post_id, '_offer_rules', $offer_rules);

            if (isset($_POST ['post_title'])) {
                update_post_meta($post_id, 'offer_title', $_POST ['post_title']);
            } else {
                delete_post_meta($post_id, 'offer_title', array());
            }

            if (isset($_POST ['target_product_ids'])) :
                if ( $sa_smart_offers->is_wc_gte_23() ) {
                    $target_products = explode( ',', $_POST ['target_product_ids'] );
                } else {
                    $target_products = array();
                    $ids = $_POST ['target_product_ids'];
                    foreach ($ids as $id) :
                        if ($id && $id > 0)
                            $target_products [] = $id;
                    endforeach;
                }
                update_post_meta($post_id, 'target_product_ids', implode(',', $target_products));
            else :
                update_post_meta($post_id, 'target_product_ids', '');
            endif;

            if (isset($_POST ['offer_price'])) :
                update_post_meta($post_id, 'offer_price', $_POST ['offer_price']);
            else :
                delete_post_meta($post_id, 'offer_price');
            endif;

            if (isset($_POST ['discount_type'])) :
                update_post_meta($post_id, 'discount_type', $_POST ['discount_type']);
            else :
                delete_post_meta($post_id, 'discount_type');
            endif;

            $offer_rule_page_options = array();

            if (isset($_POST ['offer_rule_home_page'])) :
                update_post_meta($post_id, 'offer_rule_home_page', $_POST ['offer_rule_home_page']);
                $offer_rule_page_options [] = "home_page";
            else :
                delete_post_meta($post_id, 'offer_rule_home_page');
            endif;

            if (isset($_POST ['offer_rule_cart_page'])) :
                $offer_rule_page_options [] = "cart_page";
                update_post_meta($post_id, 'offer_rule_cart_page', $_POST ['offer_rule_cart_page']);
            else :
                delete_post_meta($post_id, 'offer_rule_cart_page');
            endif;

            if (isset($_POST ['offer_rule_checkout_page'])) :
                $offer_rule_page_options [] = "checkout_page";
                update_post_meta($post_id, 'offer_rule_checkout_page', $_POST ['offer_rule_checkout_page']);
            else :
                delete_post_meta($post_id, 'offer_rule_checkout_page');
            endif;

            if (isset($_POST ['offer_rule_post_checkout_page'])) :
                $offer_rule_page_options [] = "post_checkout_page";
                update_post_meta($post_id, 'offer_rule_post_checkout_page', $_POST ['offer_rule_post_checkout_page']);
            else :
                delete_post_meta($post_id, 'offer_rule_post_checkout_page');
            endif;

            if (isset($_POST ['offer_rule_thankyou_page'])) :
                update_post_meta($post_id, 'offer_rule_thankyou_page', $_POST ['offer_rule_thankyou_page']);
                $offer_rule_page_options [] = "thankyou_page";
            else :
                delete_post_meta($post_id, 'offer_rule_thankyou_page');
            endif;

            if (isset($_POST ['offer_rule_myaccount_page'])) :
                update_post_meta($post_id, 'offer_rule_myaccount_page', $_POST ['offer_rule_myaccount_page']);
                $offer_rule_page_options [] = "myaccount_page";
            else :
                delete_post_meta($post_id, 'offer_rule_myaccount_page');
            endif;

            if (isset($_POST ['offer_rule_any_page'])) :
                update_post_meta($post_id, 'offer_rule_any_page', $_POST ['offer_rule_any_page']);
                $offer_rule_page_options [] = "any_page";
            else :
                delete_post_meta($post_id, 'offer_rule_any_page');
            endif;

            if ($offer_rule_page_options) {
                $page_options_value = implode(',', $offer_rule_page_options);
                update_post_meta($post_id, 'offer_rule_page_options', $page_options_value);
            } else {
                delete_post_meta($post_id, 'offer_rule_page_options');
            }

            if (isset($_POST ['so_show_offer_as'])) :
                update_post_meta($post_id, 'so_show_offer_as', $_POST ['so_show_offer_as']);
            else :
                delete_post_meta($post_id, 'so_show_offer_as');
            endif;

            $actions_on_accept = array();

            if ( isset( $_POST['sa_add_to_cart'] ) ) {
                $actions_on_accept['add_to_cart'] = 'yes';
            } else {
                $actions_on_accept['add_to_cart'] = 'no';
            }

            if (isset($_POST ['sa_remove_prods_from_cart'])) {

                $prods_ids_to_remove = array();
                $prods_ids_to_remove = ( $sa_smart_offers->is_wc_gte_23() ) ? explode( ',', $_POST ['remove_prods_from_cart'] ) : $_POST ['remove_prods_from_cart'];
                
                if ( in_array('all', $prods_ids_to_remove) ) {
                    $actions_on_accept[$_POST ['sa_remove_prods_from_cart']] = 'all';
                } else {
                    $prods_ids_to_remove = array();
                    $prods_ids_to_remove = ( $sa_smart_offers->is_wc_gte_23() ) ? array_filter( array_map( 'absint', explode( ',', $_POST ['remove_prods_from_cart'] ) ) ) : $_POST ['remove_prods_from_cart'];

                    if (count($prods_ids_to_remove) > 0) {
                        $prod_ids = implode(',', $prods_ids_to_remove);
                        $actions_on_accept[$_POST ['sa_remove_prods_from_cart']] = $prod_ids;
                    }
                }
            }

            if (isset($_POST ['sa_apply_coupon'])) {
                $apply_coupons = array();
                if ( ! empty( $_POST ['sa_coupon_title'] ) ) {
                    $apply_coupons = ( $sa_smart_offers->is_wc_gte_23() ) ? array_filter( array_map( 'trim', explode( ',', $_POST ['sa_coupon_title'] ) ) ) : $_POST ['sa_coupon_title'];
                }

                if (count($apply_coupons) > 0) {
                    $coupons = implode(',', $apply_coupons);
                    $actions_on_accept[$_POST ['sa_apply_coupon']] = $coupons;
                }
            }

            if (isset($_POST ['accepted_offer_ids'])) {
                $offer_ids_on_accept = array();
                if( ! empty( $_POST ['accept_offer_ids'] ) ) {
                    $offer_ids_on_accept = ( $sa_smart_offers->is_wc_gte_23() ) ? array_filter( array_map( 'absint', explode( ',', $_POST['accept_offer_ids'] ) ) ) : $_POST ['accept_offer_ids'];
                }

                if(count($offer_ids_on_accept) > 0) {
                    $accept_ids = implode(',', $offer_ids_on_accept);
                    $actions_on_accept[$_POST ['accepted_offer_ids']] = $accept_ids;
                }
            }

            if (isset($_POST ['sa_redirect_to_url'])) {
                if (isset($_POST ['accept_redirect_url']) && !empty($_POST ['accept_redirect_url'])) {
                    $actions_on_accept[$_POST ['sa_redirect_to_url']] = $_POST ['accept_redirect_url'];
                }
            }

            if (isset($_POST ['sa_buy_now'])) {
                $actions_on_accept['buy_now'] = true;
            }

            if ($actions_on_accept) {
                update_post_meta($post_id, 'so_actions_on_accept', $actions_on_accept);
            } else {
                delete_post_meta($post_id, 'so_actions_on_accept');
            }

            if (isset($_POST ['sa_smart_offer_if_denied'])) {
                update_post_meta($post_id, 'sa_smart_offer_if_denied', $_POST ['sa_smart_offer_if_denied']);
                if ($_POST ['sa_smart_offer_if_denied'] == "url") {
                    $text_option = "text_" . $_POST ['sa_smart_offer_if_denied'];
                    update_post_meta($post_id, 'url', $_POST [$text_option]);
                } elseif ($_POST ['sa_smart_offer_if_denied'] == "offer_page") {
                    if (!empty($_POST ['offer_ids'])) {
                        $offers = array();
                        $ids = ( $sa_smart_offers->is_wc_gte_23() ) ? array_filter( array_map( 'absint', explode( ',', $_POST['offer_ids'] ) ) ) : $_POST ['offer_ids'];
                        foreach ($ids as $id) :
                            if ($id && $id > 0)
                                $offers [] = $id;
                        endforeach;
                        update_post_meta($post_id, 'url', implode(',', $offers));
                    }
                } elseif ($_POST ['sa_smart_offer_if_denied'] == "particular_page") {
                    update_post_meta($post_id, 'url', $_POST ['page_id']);
                } else {
                    delete_post_meta($post_id, 'url');
                }
            } else {
                update_post_meta($post_id, 'sa_smart_offer_if_denied', "order_page");
                // if its "order_page", then do not save url
                delete_post_meta($post_id, 'url');
            }

            // NEWLY ADDED CODE TO REMOVE SKIPPED IDS FROM CUSTOMERS RECORD IF IT IS UNCHECKED. 
            $skip_permanently = get_post_meta($post_id, 'sa_smart_offer_if_denied_skip_permanently', true);

            if ($skip_permanently && !isset($_POST['sa_smart_offer_if_denied_skip_permanently'])) {

                $users_skipped_ids_args = array (
                                'meta_query'     => array(
                                                            array(
                                                                'key'       => 'customer_skipped_offers',
                                                            ),
                                                        ),
                                'fields'         => 'ID'
                            );

                // The User Query
                $users_skipped_ids = new WP_User_Query( $users_skipped_ids_args );

                $new_skipped_ids = array();

                if ( $users_skipped_ids->total_users > 0 ) {

                    foreach ( $users_skipped_ids->results as $user_id) {

                        $skipped_ids = get_user_meta( $user_id, 'customer_skipped_offers', true );

                        if ( in_array( $post_id, $skipped_ids ) ) {
                            $key = array_search($post_id, $skipped_ids);
                            unset($skipped_ids [$key]);
                            $new_skipped_ids[$user_id] = $skipped_ids;
                        }
                    }

                }

                $query_case = array();
                $user_ids = array();

                if (count($new_skipped_ids > 0)) {

                    $wpdb->query("SET SESSION group_concat_max_len=999999");
                    foreach ($new_skipped_ids as $id => $meta_value) {

                        $user_ids[] = $id;
                        $query_case[] = "WHEN " . $id . " THEN '" . $wpdb->_real_escape(maybe_serialize($meta_value)) . "'";
                    }
                    $update_query_for_customer_skipped_ids = " UPDATE {$wpdb->prefix}usermeta  
                                                                                    SET meta_value = CASE user_id " . implode("\n", $query_case) . " 
                                                                                    END 
                                                                                    WHERE user_id IN (" . implode(",", $user_ids) . ")
                                                                                    AND meta_key = 'customer_skipped_offers'
                                                                                    ";
                }

                $wpdb->query($update_query_for_customer_skipped_ids);
            }

            if (isset($_POST ['sa_smart_offer_if_denied_skip_permanently'])) {
                update_post_meta($post_id, 'sa_smart_offer_if_denied_skip_permanently', $_POST ['sa_smart_offer_if_denied_skip_permanently']);
            } else {
                delete_post_meta($post_id, 'sa_smart_offer_if_denied_skip_permanently');
            }

            $position_accept = strpos($_POST ['content'], '[so_acceptlink]');
            $position_skip = strpos($_POST ['content'], '[so_skiplink]');
            $sc_position = strpos($_POST ['content'], '[so_product_variants');
            
            if (!$position_accept || !$position_skip) {
                $offered_prod_instance = $sa_smart_offers->get_product(implode(',', $target_products));
                $url = admin_url('post.php?action=edit&message=2&post=' . $post_id);
                if ($sc_position === false && ( ($sa_smart_offers->is_wc_gte_20() && $offered_prod_instance->product_type == 'variable') || ((!$sa_smart_offers->is_wc_gte_20() && $offered_prod_instance->product_type == 'variable' && !isset($offered_prod_instance->variation_id)) ) )) {
                    $url = add_query_arg('show_sc_msg', true, $url);
                }
                wp_safe_redirect($url);
                exit();
            }
        }

        /**
	 * Show metaboxes in SO
	 */
        function add_smart_offers_custom_box() {
            global $pagenow, $typenow;

            if ( 'edit.php' != $pagenow && $typenow != 'smart_offers' ) return;

            add_meta_box('so-whats-the-offer', __("What's the offer? ", SA_Smart_Offers::$text_domain), array(&$this, 'so_whats_the_offer_meta_box'), 'smart_offers', 'normal', 'high');
            add_meta_box('smart-offers-desc', __('Offer Description', SA_Smart_Offers::$text_domain), array(&$this, 'so_add_editor'), 'smart_offers', 'normal', 'high');
            add_meta_box('so-where-to-show-offer', __('Which page/s to show this offer on? ', SA_Smart_Offers::$text_domain), array(&$this, 'so_where_to_show_offer'), 'smart_offers', 'normal', 'high');
            add_meta_box('so-when-to-show-offer', __('When to show this offer? ', SA_Smart_Offers::$text_domain), array(&$this, 'so_when_to_show_offer'), 'smart_offers', 'normal', 'high');
            add_meta_box('so-action-when-offer-skipped', __('What to do when this offer is accepted/skipped?  ', SA_Smart_Offers::$text_domain), array(&$this, 'so_when_offer_is_skipped'), 'smart_offers', 'normal', 'high');

            remove_meta_box('woothemes-settings', 'smart_offers', 'normal');
            remove_meta_box('commentstatusdiv', 'smart_offers', 'normal');
            remove_meta_box('slugdiv', 'smart_offers', 'normal');
        }

        /**
	 * Change the post title
	 */
        function woo_smart_offers_enter_title_here($text, $post) {
            if ($post->post_type == 'smart_offers')
                return __('Offer Title', SA_Smart_Offers::$text_domain);
            return $text;
        }

        /**
	 * Show What's the Offer meta box
	 */
        function so_whats_the_offer_meta_box() {
            global $post, $sa_smart_offers;

            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            $assets_path = str_replace(array('http:', 'https:'), '', $sa_smart_offers->global_wc()->plugin_url()) . '/assets/';

            if ($sa_smart_offers->is_wc_gte_21()) {

                $woocommerce_witepanel_params = array('ajax_url' => admin_url('admin-ajax.php'), 'search_products_nonce' => wp_create_nonce("search-products"), 'calendar_image' => $sa_smart_offers->global_wc()->plugin_url() . '/assets/images/calendar.png');
                // Register scripts
                wp_enqueue_script('woocommerce_admin', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array('jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip'), $sa_smart_offers->global_wc()->version);
                if ( $sa_smart_offers->is_wc_gte_22() ) {
                    wp_enqueue_script( 'wc-admin-meta-boxes', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'accounting', 'round', 'ajax-chosen', 'chosen', 'plupload-all' ), WC_VERSION );
                    wp_enqueue_script( 'wc-admin-product-meta-boxes', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/meta-boxes-product' . $suffix . '.js', array( 'wc-admin-meta-boxes' ), WC_VERSION );
                    wp_enqueue_script( 'wc-admin-variation-meta-boxes', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/meta-boxes-product-variation' . $suffix . '.js', array( 'wc-admin-meta-boxes' ), WC_VERSION );

                    $params = array(
                        'post_id'                             => isset( $post->ID ) ? $post->ID : '',
                        'plugin_url'                          => $sa_smart_offers->global_wc()->plugin_url(),
                        'ajax_url'                            => admin_url( 'admin-ajax.php' ),
                        'woocommerce_placeholder_img_src'     => wc_placeholder_img_src(),
                        'add_variation_nonce'                 => wp_create_nonce("add-variation"),
                        'link_variation_nonce'                => wp_create_nonce("link-variations"),
                        'delete_variations_nonce'             => wp_create_nonce("delete-variations"),
                        'i18n_link_all_variations'            => esc_js( __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max 50 per run).', SA_Smart_Offers::$text_domain ) ),
                        'i18n_enter_a_value'                  => esc_js( __( 'Enter a value', SA_Smart_Offers::$text_domain ) ),
                        'i18n_enter_a_value_fixed_or_percent' => esc_js( __( 'Enter a value (fixed or %)', SA_Smart_Offers::$text_domain ) ),
                        'i18n_delete_all_variations'          => esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', SA_Smart_Offers::$text_domain ) ),
                        'i18n_last_warning'                   => esc_js( __( 'Last warning, are you sure?', SA_Smart_Offers::$text_domain ) ),
                        'i18n_choose_image'                   => esc_js( __( 'Choose an image', SA_Smart_Offers::$text_domain ) ),
                        'i18n_set_image'                      => esc_js( __( 'Set variation image', SA_Smart_Offers::$text_domain ) ),
                        'i18n_variation_added'                => esc_js( __( "variation added", SA_Smart_Offers::$text_domain ) ),
                        'i18n_variations_added'               => esc_js( __( "variations added", SA_Smart_Offers::$text_domain ) ),
                        'i18n_no_variations_added'            => esc_js( __( "No variations added", SA_Smart_Offers::$text_domain ) ),
                        'i18n_remove_variation'               => esc_js( __( 'Are you sure you want to remove this variation?', SA_Smart_Offers::$text_domain ) ),
                        'i18n_scheduled_sale_start'           => esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', SA_Smart_Offers::$text_domain ) ),
                        'i18n_scheduled_sale_end'             => esc_js( __( 'Sale end date  (YYYY-MM-DD format or leave blank)', SA_Smart_Offers::$text_domain ) )
                    );

                    wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $woocommerce_witepanel_params );
                    wp_localize_script( 'wc-admin-variation-meta-boxes', 'woocommerce_admin_meta_boxes_variations', $params );
                } else {
                    wp_enqueue_script('woocommerce_admin_meta_boxes', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js', array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'accounting', 'round'), $sa_smart_offers->global_wc()->version);
                    wp_enqueue_script('woocommerce_admin_meta_boxes_variations', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/meta-boxes-variations' . $suffix . '.js', array('jquery', 'jquery-ui-sortable'), $sa_smart_offers->global_wc()->version);
                    wp_localize_script('woocommerce_admin_meta_boxes', 'woocommerce_admin_meta_boxes', $woocommerce_witepanel_params);
                }

                if ( $sa_smart_offers->is_wc_gte_23() ) {
                    if ( ! wp_script_is( 'select2', 'registered' ) ) {
                        wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/admin/select2' . $suffix . '.js', array( 'jquery' ), '3.5.2' );
                    }
                    if ( ! wp_script_is( 'wc-enhanced-select', 'registered' ) ) {
                        wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'select2' ), WC_VERSION );
                    }
                    $smart_offers_select_params = array(
                            'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', SA_Smart_Offers::$text_domain ),
                            'ajax_url'                  => admin_url( 'admin-ajax.php' ),
                            'search_products_nonce'     => wp_create_nonce( 'search-products' ),
                            'search_customers_nonce'    => wp_create_nonce( 'search-customers' )
                        );
                    wp_localize_script( 'select2', 'wc_enhanced_select_params', $smart_offers_select_params );

                    $locale  = localeconv();
                    $decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';

                    $woocommerce_admin_params = array(
                        'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', SA_Smart_Offers::$text_domain ), $decimal ),
                        'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', SA_Smart_Offers::$text_domain ), wc_get_price_decimal_separator() ),
                        'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', SA_Smart_Offers::$text_domain ),
                        'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', SA_Smart_Offers::$text_domain ),
                        'decimal_point'                     => $decimal,
                        'mon_decimal_point'                 => wc_get_price_decimal_separator()
                    );

                    wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $woocommerce_admin_params );
                } else {
                    wp_enqueue_script('ajax-chosen', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery' . $suffix . '.js', array('jquery', 'chosen'), $sa_smart_offers->global_wc()->version);
                    wp_enqueue_script('chosen', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/chosen/chosen.jquery' . $suffix . '.js', array('jquery'), $sa_smart_offers->global_wc()->version);
                }

                wp_enqueue_style('woocommerce_admin_styles', $sa_smart_offers->global_wc()->plugin_url() . '/assets/css/admin.css');

                if ( $sa_smart_offers->is_wc_gte_23() ) {
                    wp_enqueue_script( 'select2' );
                    wp_enqueue_script( 'wc-enhanced-select' );
                    wp_enqueue_style( 'select2', $assets_path . 'css/select2.css' );
                } else {
                    wp_enqueue_style('woocommerce_chosen_styles', $assets_path . 'css/chosen.css');
                }

            } else {
                // Register scripts
                wp_register_script('woocommerce_admin', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/woocommerce_admin' . $suffix . '.js', array('jquery', 'jquery-ui-widget', 'jquery-ui-core'), '1.0');
                wp_register_script('woocommerce_writepanel', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/admin/write-panels' . $suffix . '.js', array('jquery'));
                wp_register_script('ajax-chosen', $sa_smart_offers->global_wc()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery' . $suffix . '.js', array('jquery'), '1.0');

                wp_enqueue_script('woocommerce_admin');
                wp_enqueue_script('woocommerce_writepanel');
                wp_enqueue_script('ajax-chosen');

                $woocommerce_witepanel_params = array('ajax_url' => admin_url('admin-ajax.php'), 'search_products_nonce' => wp_create_nonce("search-products"), 'calendar_image' => $sa_smart_offers->global_wc()->plugin_url() . '/assets/images/calendar.png');

                wp_localize_script('woocommerce_writepanel', 'woocommerce_writepanel_params', $woocommerce_witepanel_params);

                wp_enqueue_style('woocommerce_admin_styles', $sa_smart_offers->global_wc()->plugin_url() . '/assets/css/admin.css');
                wp_enqueue_style('jquery-ui-style', (is_ssl()) ? 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' : 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
                wp_enqueue_style('woocommerce_chosen_styles', $sa_smart_offers->global_wc()->plugin_url() . '/assets/css/chosen.css');
            }
            ?>
            
            <script type="text/javascript">

                jQuery(document).ready(function() {

                    <?php if ( ! $sa_smart_offers->is_wc_gte_23() ) { ?>

                        function set_unique_offer_product() {

                            setTimeout(function() {

                                <?php if ($sa_smart_offers->is_wc_gte_21()) { ?>
                                            if (jQuery('div#target_product_ids_chosen ul.chosen-choices li').length >= 2) {

                                                jQuery('div#target_product_ids_chosen ul.chosen-choices li.search-field').css('visibility', 'hidden');
                                                jQuery('div#target_product_ids_chosen div.chosen-drop').css('display', 'none');

                                            } else {

                                                jQuery('div#target_product_ids_chosen ul.chosen-choices li.search-field').css('visibility', 'visible');
                                                jQuery('div#target_product_ids_chosen div.chosen-drop').css('display', 'block');

                                            }
                                <?php } else { ?>

                                            if (jQuery('div#target_product_ids_chzn ul.chzn-choices li').length >= 2) {

                                                jQuery('div#target_product_ids_chzn ul.chzn-choices li.search-field').css('visibility', 'hidden');
                                                jQuery('div#target_product_ids_chzn div.chzn-drop').css('display', 'none');

                                            } else {

                                                jQuery('div#target_product_ids_chzn ul.chzn-choices li.search-field').css('visibility', 'visible');
                                                jQuery('div#target_product_ids_chzn div.chzn-drop').css('display', 'block');

                                            }


                                <?php } ?>

                            }, 300);
                        }

                        set_unique_offer_product();

                        jQuery('select#target_product_ids').change(function() {
                            set_unique_offer_product();
                        });

                    <?php } ?>

                });

            </script>

            <div id="so_whats_offer_panel" class="panel woocommerce_options_panel">
                <p class="form-field">
                    <label for="target_product_ids"><?php _e('Offered Product', SA_Smart_Offers::$text_domain); ?></label>

                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>

                        <input type="hidden" class="wc-product-search" style="width: 50%;" id="target_product_ids" name="target_product_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="false" data-selected="<?php

                            $product_id = absint( get_post_meta( $post->ID, 'target_product_ids', true ) );
                            
                            if ( ! empty( $product_id ) ) {

                                $product = $sa_smart_offers->get_product( $product_id );

                                echo esc_attr( wp_kses_post( $product->get_formatted_name() ) );
                                
                            } else {

                                echo '';

                            }

                        ?>" value="<?php echo ( ! empty( $product_id ) ) ? $product_id : ''; ?>" />

                    <?php } else { ?>

                        <select id="target_product_ids" name="target_product_ids[]" class="ajax_chosen_select_products_and_variations" multiple="multiple" data-placeholder="<?php _e('Search for a product...', SA_Smart_Offers::$text_domain); ?>">
                            <?php
                                $product_ids = get_post_meta($post->ID, 'target_product_ids', true);
                                if ($product_ids) {
                                    $product_ids = explode(',', $product_ids);
                                    foreach ($product_ids as $product_id) {
                                        $product = $sa_smart_offers->get_product($product_id);
                                        $title = $sa_smart_offers->get_formatted_product_name($product);
                                        $sku = get_post_meta($product_id, '_sku', true);

                                        if (!$title)
                                            continue;

                                        if (isset($sku) && $sku)
                                            $sku = ' (SKU: ' . $sku . ')';

                                        echo '<option value="' . $product_id . '" selected="selected">' . $title . $sku . '</option>';
                                    }
                                }
                            ?>
                        </select> 

                    <?php } ?>
                    <img class="help_tip" data-tip='<?php _e('This product would be shown as an offer and on accepting this offer, this product would be added to cart ', SA_Smart_Offers::$text_domain); ?>' src="<?php echo $sa_smart_offers->global_wc()->plugin_url(); ?>/assets/images/help.png" />
                </p>

                <?php
                    $discount_types = array(
                                            'fixed_price' => __(get_woocommerce_currency_symbol() . ' - Fixed Price', SA_Smart_Offers::$text_domain),
                                            'price_discount' => __(get_woocommerce_currency_symbol() . ' - Discount', SA_Smart_Offers::$text_domain),
                                            'percent_discount' => '% - Discount'
                                        );
                ?>

                <p class="form-field offer_price_field ">
                    <label for="offer_price"><?php _e('Offer At', SA_Smart_Offers::$text_domain); ?></label>
                    <input type="number" step="any" class="short" name="offer_price" id="offer_price" value="<?php echo get_post_meta($post->ID, 'offer_price', true) ?>"> 
                    <select id="discount_type" name="discount_type" class="select short">
                       <?php
                           foreach ($discount_types as $key => $value) {
                               echo "<option value='$key' " . selected($key, get_post_meta($post->ID, 'discount_type', true)) . "> $value </option>";
                           }
                       ?>
                    </select>
                    <span class="description"><?php _e('Enter an amount/discount as a promotional price for above offered product e.g. 2.99', SA_Smart_Offers::$text_domain); ?></span>

                </p>

            </div>
                <?php
            }

            //
            function so_add_editor() {
                global $post;
                ?>
            <script type="text/javascript">
                jQuery(function() {

                    jQuery('a#missing_shortcode').click(function() {
                        if ((jQuery('textarea#content').css('display') == 'none')) {
                            jQuery('textarea#content').css('display', "");
                        }

                        var postContent = jQuery('textarea#content').val();

                        var position = postContent.indexOf('<div class="so_accept">');
                        if (position == -1) {
                            position = postContent.indexOf('<div class="so_skip">');
                        }

                        var trimmedContent = '';
                        if (position > 0) {
                            trimmedContent = postContent.substr(0, position);
                            trimmedContent += '<div class="so_accept"><a href="[so_acceptlink]">Yes, Add to Cart</a></div>';
                            trimmedContent += '<div class="so_skip"><a href="[so_skiplink]">No, Skip this offer</a></div>';

                        } else {

                            trimmedContent = postContent + '<div class="so_accept"><a href="[so_acceptlink]">Yes, Add to Cart</a></div>';
                            trimmedContent += '<div class="so_skip"><a href="[so_skiplink]">No, Skip this offer</a></div>';
                        }

                        jQuery('textarea#content').val(trimmedContent);
                        jQuery('input#publish').trigger('click');
                        return false;
                    });
                });
            </script>

            <?php
            $settings = array('quicktags' => array('buttons' => 'em,strong,link'), 'textarea_name' => 'content', 'quicktags' => true, 'tinymce' => true);

            wp_editor(htmlspecialchars_decode($post->post_content), 'content', $settings);
        }

        /**
	 * Show Where to show Offer meta box
	 */
        function so_where_to_show_offer() {
            global $post;

            $show_offer_as = get_post_meta($post->ID, 'so_show_offer_as', true);
            if (empty($show_offer_as)) {
                $show_offer_as = "offer_as_inline";
            }
            ?>
            <div id="so_where_to_offer" class="panel woocommerce_options_panel">

                <p class="form-field page_option_for_offer_field">
                    <fieldset name="page_options">
                        <label id="page_option_for_offer" for="page_option_for_offer"><strong><?php echo __( 'Show this Offer on', SA_Smart_Offers::$text_domain ); ?>:</strong></label>
                        <fieldset>
                            <fieldset>
                                <input type="checkbox" id="offer_rule_home_page" name="offer_rule_home_page" class="checkbox" value="yes" 
                                    <?php if (get_post_meta($post->ID, 'offer_rule_home_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('Home page as a popup', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset>
                                <input type="checkbox" id="offer_rule_cart_page" name="offer_rule_cart_page" class="checkbox" value="yes" 
                                    <?php if (get_post_meta($post->ID, 'offer_rule_cart_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('Cart page', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset>
                                <input type="checkbox" id="offer_rule_checkout_page" name="offer_rule_checkout_page" class="checkbox" value="yes"
                                    <?php if (get_post_meta($post->ID, 'offer_rule_checkout_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('Before Checkout', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset>
                                <input type="checkbox" id="offer_rule_post_checkout_page" name="offer_rule_post_checkout_page" class="checkbox" value="yes"
                                    <?php if (get_post_meta($post->ID, 'offer_rule_post_checkout_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('After Checkout', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset>
                                <input type="checkbox" id="offer_rule_thankyou_page" name="offer_rule_thankyou_page" class="checkbox" value="yes"
                                    <?php if (get_post_meta($post->ID, 'offer_rule_thankyou_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('Order Complete page', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset><input type="checkbox" id="offer_rule_myaccount_page" name="offer_rule_myaccount_page" class="checkbox" value="yes"
                                    <?php if (get_post_meta($post->ID, 'offer_rule_myaccount_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('My Account page', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>
                            <fieldset><input type="checkbox" id="offer_rule_any_page" name="offer_rule_any_page" class="checkbox" value="yes"
                                    <?php if (get_post_meta($post->ID, 'offer_rule_any_page', true) == "yes") echo 'checked="checked"'; ?> />
                                <span class="description"><?php _e('Any other page where shortcode is added', SA_Smart_Offers::$text_domain); ?></span>
                            </fieldset>    
                        </fieldset>
                    </fieldset>
                </p>
            </div>

            <div id="so_offer_as" class="panel woocommerce_options_panel">
                <table class="form-table">
                    <tbody>
                        <tr valign="top" class="">
                            <th scope="row" class="titledesc"><?php _e('Show this Offer as:', SA_Smart_Offers::$text_domain); ?></th>
                            <td class="forminp forminp-checkbox" id="show-offer-images" >
                                <fieldset class="">
                                    <legend class="screen-reader-text"><span></span></legend>
                                    <label for="img_offer_as_inline">
                                        <div class='sprite show-offer-inline'></div>
                                    </label> 										
                                </fieldset>
                            </td>
                            <td class="forminp forminp-checkbox">
                                <fieldset class="">
                                    <legend class="screen-reader-text"><span></span></legend>
                                    <label for="img_offer_as_popup">
                                        <div class='sprite show-offer-as-lightbox'></div>
                                    </label> 
                                </fieldset>
                            </td>
                        </tr>
                        <tr valign="top" class="">
                            <th scope="row" class="titledesc" id="show-offer-images-labels" ></th>
                            <td >
                                <fieldset class="">
                                    <legend class="screen-reader-text"><span></span></legend>
                                    <label for="offer_as_inline">
                                        <input type="radio" id="offer_as_inline" name="so_show_offer_as" class="checkbox" value="offer_as_inline" 
                                            <?php if ($show_offer_as == "offer_as_inline") echo 'checked="checked"'; ?> />
                                        <span class="description"><?php _e('Inline with page content', SA_Smart_Offers::$text_domain); ?></span>
                                </fieldset>
                            </td>
                            <td >
                                <fieldset class="">
                                    <legend class="screen-reader-text"><span></span></legend>
                                    <label for="offer_as_popup">
                                        <input type="radio" id="offer_as_popup" name="so_show_offer_as" class="checkbox" value="offer_as_popup"
                                            <?php if ($show_offer_as == "offer_as_popup") echo 'checked="checked"'; ?> />
                                        <span class="description"><?php _e('Lightbox / Popup / Modal dialog', SA_Smart_Offers::$text_domain); ?></span>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>                                  
            <?php
        }

        /**
    	 * Show Offer Rules meta box
    	 */
        function so_when_to_show_offer() {

            include_once("class-so-admin-offer-rule.php");
        }

        /**
    	 * Show Action to be taken when offer is accept/skip meta box
    	 */
        function so_when_offer_is_skipped() {
            global $post, $sa_smart_offers, $post_id;

            $action_on_accept = get_post_meta($post->ID, 'so_actions_on_accept', true);
            $prod_ids_to_remove =  $apply_coupon = $offer_ids_on_accept = null;
            if (empty($action_on_accept)) {
                $add_to_cart = true;
            } else {

                $add_to_cart = ( isset($action_on_accept['add_to_cart']) && $action_on_accept['add_to_cart'] == 'yes' ) ? true : false;

                $buy_now = ( isset($action_on_accept['buy_now']) && $action_on_accept['buy_now'] == true ) ? true : false;

                if (isset($action_on_accept['remove_prods_from_cart'])) {
                    $remove_prods_from_cart = true;
                    $prod_ids_to_remove = $action_on_accept['remove_prods_from_cart'];
                }

                if (isset($action_on_accept['sa_apply_coupon'])) {
                    $sa_apply_coupon = true;
                    $apply_coupon = $action_on_accept['sa_apply_coupon'];
                }

                if (isset($action_on_accept['accepted_offer_ids'])) {
                    $accepted_offer_ids = true;
                    $offer_ids_on_accept = $action_on_accept['accepted_offer_ids'];
                }

                if (isset($action_on_accept['sa_redirect_to_url'])) {
                    $sa_redirect_to_url = true;
                }
            }
            ?>
            <script type="text/javascript">

                jQuery(document).ready(function() {

                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>

                        if ( typeof getEnhancedSelectFormatString == "undefined" ) {
                            function getEnhancedSelectFormatString() {
                                var formatString = {
                                    formatMatches: function( matches ) {
                                        if ( 1 === matches ) {
                                            return wc_enhanced_select_params.i18n_matches_1;
                                        }

                                        return wc_enhanced_select_params.i18n_matches_n.replace( '%qty%', matches );
                                    },
                                    formatNoMatches: function() {
                                        return wc_enhanced_select_params.i18n_no_matches;
                                    },
                                    formatAjaxError: function( jqXHR, textStatus, errorThrown ) {
                                        return wc_enhanced_select_params.i18n_ajax_error;
                                    },
                                    formatInputTooShort: function( input, min ) {
                                        var number = min - input.length;

                                        if ( 1 === number ) {
                                            return wc_enhanced_select_params.i18n_input_too_short_1
                                        }

                                        return wc_enhanced_select_params.i18n_input_too_short_n.replace( '%qty%', number );
                                    },
                                    formatInputTooLong: function( input, max ) {
                                        var number = input.length - max;

                                        if ( 1 === number ) {
                                            return wc_enhanced_select_params.i18n_input_too_long_1
                                        }

                                        return wc_enhanced_select_params.i18n_input_too_long_n.replace( '%qty%', number );
                                    },
                                    formatSelectionTooBig: function( limit ) {
                                        if ( 1 === limit ) {
                                            return wc_enhanced_select_params.i18n_selection_too_long_1;
                                        }

                                        return wc_enhanced_select_params.i18n_selection_too_long_n.replace( '%qty%', number );
                                    },
                                    formatLoadMore: function( pageNumber ) {
                                        return wc_enhanced_select_params.i18n_load_more;
                                    },
                                    formatSearching: function() {
                                        return wc_enhanced_select_params.i18n_searching;
                                    }
                                };

                                return formatString;
                            }
                        }

                        var bindOffersSelect2 = function() {

                            jQuery( ':input.so-offer-search' ).filter( ':not(.enhanced)' ).each( function() {
                                var select2_args = {
                                    allowClear:  jQuery( this ).data( 'allow_clear' ) ? true : false,
                                    placeholder: jQuery( this ).data( 'placeholder' ),
                                    minimumInputLength: jQuery( this ).data( 'minimum_input_length' ) ? jQuery( this ).data( 'minimum_input_length' ) : '3',
                                    escapeMarkup: function( m ) {
                                        return m;
                                    },
                                    ajax: {
                                        url:         '<?php echo admin_url("admin-ajax.php"); ?>',
                                        dataType:    'json',
                                        quietMillis: 250,
                                        data: function( term, page ) {
                                            return {
                                                term:     term,
                                                action:   jQuery( this ).data( 'action' ) || 'woocommerce_json_search_offers',
                                                security: '<?php echo wp_create_nonce("search-offers"); ?>'
                                            };
                                        },
                                        results: function( data, page ) {
                                            var terms = [];
                                            if ( data ) {
                                                jQuery.each( data, function( id, text ) {
                                                    terms.push( { id: id, text: text } );
                                                });
                                            }
                                            return { results: terms };
                                        },
                                        cache: true
                                    }
                                };

                                if ( jQuery( this ).data( 'multiple' ) === true ) {
                                    select2_args.multiple = true;
                                    select2_args.initSelection = function( element, callback ) {
                                        var data     = jQuery.parseJSON( element.attr( 'data-selected' ) );
                                        var selected = [];

                                        jQuery( element.val().split( "," ) ).each( function( i, val ) {
                                            selected.push( { id: val, text: data[ val ] } );
                                        });
                                        return callback( selected );
                                    };
                                    select2_args.formatSelection = function( data ) {
                                        return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
                                    };
                                } else {
                                    select2_args.multiple = false;
                                    select2_args.initSelection = function( element, callback ) {
                                        var data = {id: element.val(), text: element.attr( 'data-selected' )};
                                        return callback( data );
                                    };
                                }

                                select2_args = jQuery.extend( select2_args, getEnhancedSelectFormatString() );

                                jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
                            });

                        };

                        bindOffersSelect2();

                        var bindCouponsSelect2 = function() {

                            jQuery( ':input.wc-coupon-search' ).filter( ':not(.enhanced)' ).each( function() {
                                var select2_args = {
                                    allowClear:  jQuery( this ).data( 'allow_clear' ) ? true : false,
                                    placeholder: jQuery( this ).data( 'placeholder' ),
                                    minimumInputLength: jQuery( this ).data( 'minimum_input_length' ) ? jQuery( this ).data( 'minimum_input_length' ) : '3',
                                    escapeMarkup: function( m ) {
                                        return m;
                                    },
                                    ajax: {
                                        url:         '<?php echo admin_url("admin-ajax.php"); ?>',
                                        dataType:    'json',
                                        quietMillis: 250,
                                        data: function( term, page ) {
                                            return {
                                                term:     term,
                                                action:   jQuery( this ).data( 'action' ) || 'woocommerce_json_search_coupons',
                                                security: '<?php echo wp_create_nonce("search-coupons"); ?>'
                                            };
                                        },
                                        results: function( data, page ) {
                                            var terms = [];
                                            if ( data ) {
                                                jQuery.each( data, function( id, text ) {
                                                    terms.push( { id: id, text: text } );
                                                });
                                            }
                                            return { results: terms };
                                        },
                                        cache: true
                                    }
                                };

                                if ( jQuery( this ).data( 'multiple' ) === true ) {
                                    select2_args.multiple = true;
                                    select2_args.initSelection = function( element, callback ) {
                                        var data     = jQuery.parseJSON( element.attr( 'data-selected' ) );
                                        var selected = [];

                                        jQuery( element.val().split( "," ) ).each( function( i, val ) {
                                            selected.push( { id: val, text: data[ val ] } );
                                        });
                                        return callback( selected );
                                    };
                                    select2_args.formatSelection = function( data ) {
                                        return '<div class="selected-option" data-id="' + data.id + '">' + data.text + '</div>';
                                    };
                                } else {
                                    select2_args.multiple = false;
                                    select2_args.initSelection = function( element, callback ) {
                                        var data = {id: element.val(), text: element.attr( 'data-selected' )};
                                        return callback( data );
                                    };
                                }

                                select2_args = jQuery.extend( select2_args, getEnhancedSelectFormatString() );

                                jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
                            });

                        };

                        bindCouponsSelect2();

                    <?php } else { ?>

                        var bindOffersAjaxChosen = function() {

                            jQuery("select.ajax_chosen_select_offers").ajaxChosen({
                                method: 'GET',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                afterTypeDelay: 100,
                                data: {
                                    action: 'woocommerce_json_search_offers',
                                    security: '<?php echo wp_create_nonce("search-offers"); ?>'
                                }
                            }, function(data) {

                                var terms = {};

                                jQuery.each(data, function(i, val) {
                                    terms[i] = val;
                                });

                                return terms;
                            });
                        };

                        bindOffersAjaxChosen();

                        var bindCouponsAjaxChosen = function() {

                            jQuery("select.ajax_chosen_select_coupons").ajaxChosen({
                                method: 'GET',
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                dataType: 'json',
                                afterTypeDelay: 100,
                                data: {
                                    action: 'woocommerce_json_search_coupons',
                                    security: '<?php echo wp_create_nonce("search-coupons"); ?>'
                                }
                            }, function(data) {

                                var terms = {};

                                jQuery.each(data, function(i, val) {
                                    terms[i] = val;
                                });

                                return terms;
                            });
                        };

                        bindCouponsAjaxChosen();

                    <?php } ?>

                    jQuery(".accept_input_checkboxes").change(function() {
                        var id = jQuery(this).attr('id');
                        var sa_redirect_to_url = jQuery('input#sa_redirect_to_url');
                        var buy_now = jQuery('input#buy_now');
                        var accepted_offer_ids = jQuery('input#accepted_offer_ids');

                        switch ( id ) {

                            case 'accepted_offer_ids':
                                sa_redirect_to_url.removeAttr('checked');
                                buy_now.removeAttr('checked');
                                break;

                            case 'sa_redirect_to_url':
                                accepted_offer_ids.removeAttr('checked');
                                buy_now.removeAttr('checked');
                                break;

                            case 'buy_now':
                                accepted_offer_ids.removeAttr('checked');
                                sa_redirect_to_url.removeAttr('checked');
                                break;

                        }

                    });

                });
            </script>
            <div id="so_when_offer_accepted" class="panel woocommerce_options_panel">
                <h3> <?php _e('Actions to take when offer is accepted :', SA_Smart_Offers::$text_domain); ?></h3><br/>
                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="sa_add_to_cart" id="add_to_cart" <?php if ($add_to_cart == true) echo 'checked="checked"'; ?> value="add_to_cart" >
                    <label class="accept_input_checkboxes" id="add_to_cart" for="add_to_cart">
                    <?php _e('Add the offered product to cart', SA_Smart_Offers::$text_domain); ?></label>
                </fieldset>
                <br/>
                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="sa_remove_prods_from_cart" id="remove_prods_from_cart" <?php if (isset($remove_prods_from_cart) && $remove_prods_from_cart == true) echo 'checked="checked"'; ?> value="remove_prods_from_cart">
                    <label class="accept_input_checkboxes" id="remove_prods_from_cart" for="remove_prods_from_cart">
                    <?php _e('Remove following products from the cart', SA_Smart_Offers::$text_domain); ?></label>
                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                        <input type="hidden" class="so-product-and-only-variations-search" style="width: 50%;" id="remove_prods_from_cart" name="remove_prods_from_cart" data-placeholder="<?php _e( 'Search for a product&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_products_and_only_variations" data-multiple="true" data-selected="<?php
                            $json_ids    = array();
                            if ( ! empty( $prod_ids_to_remove ) ) {
                                
                                if( $prod_ids_to_remove == 'all') {
                                    $json_ids[ $prod_ids_to_remove ] = __( 'All Products', SA_Smart_Offers::$text_domain );
                                    echo esc_attr( json_encode( $json_ids ) );
                                } else {
                                    $product_ids = array_filter( array_map( 'absint', explode(',', $prod_ids_to_remove ) ) );
                                    
                                    foreach ( $product_ids as $product_id ) {
                                        $product = $sa_smart_offers->get_product( $product_id );
                                        $sku = get_post_meta( $product_id, '_sku', true );
                                        if ( ! empty( $sku ) ) {
                                            $sku = ' (SKU: ' . $sku . ')';
                                        }
                                        $json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() . $sku );
                                    }

                                    echo esc_attr( json_encode( $json_ids ) );
                                }
                            }
                        ?>" value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>" />
                    <?php } else { ?>
                        <select id="remove_prods_from_cart" name="remove_prods_from_cart[]" class="ajax_chosen_select_products_and_only_variations" multiple="multiple" data-placeholder="<?php _e('Search for a product...', SA_Smart_Offers::$text_domain); ?>" >
                        <?php
                            if ($prod_ids_to_remove) {

                                if( $prod_ids_to_remove == 'all') {
                                    echo '<option value="all" selected="selected">' . __( 'All Products', SA_Smart_Offers::$text_domain ) . '</option>';
                                } else {

                                    $prod_ids_to_remove = explode(',', $prod_ids_to_remove);
                                    foreach ($prod_ids_to_remove as $product_id) {
                                        $product = $sa_smart_offers->get_product($product_id);
                                        $title = $sa_smart_offers->get_formatted_product_name($product);
                                        $sku = get_post_meta($product_id, '_sku', true);

                                        if (!$title)
                                            continue;

                                        if (isset($sku) && $sku)
                                            $sku = ' (SKU: ' . $sku . ')';

                                        echo '<option value="' . $product_id . '" selected="selected">' . $title . $sku . '</option>';
                                    }
                                }
                            }
                        ?>
                        </select>
                    <?php } ?>
                </fieldset>
                <br/>
                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="sa_apply_coupon" id="sa_apply_coupon" <?php if (isset($sa_apply_coupon) && $sa_apply_coupon == true) echo 'checked="checked"'; ?> value="sa_apply_coupon">
                    <label class="accept_input_checkboxes" id="sa_redirect_to_url" for="sa_apply_coupon"><?php _e('Apply Coupons', SA_Smart_Offers::$text_domain); ?></label>
                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                        <input type="hidden" class="wc-coupon-search" style="width: 50%;" id="sa_coupon_title" name="sa_coupon_title" data-placeholder="<?php _e( 'Search for a coupon&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_coupons" data-multiple="true" data-selected="<?php
                            if ( !class_exists( 'WC_Coupon' ) ) {
                                require_once( WP_PLUGIN_DIR . '/woocommerce/classes/class-wc-coupon.php' );
                            }

                            $all_discount_types = $sa_smart_offers->wc_get_coupon_types();
                            $json_coupons = array();

                            if ( ! empty( $apply_coupon ) ) {

                                $coupon_titles = array_filter( array_map( 'trim', explode( ',', $apply_coupon ) ) );

                                foreach ($coupon_titles as $coupon_title) {

                                    $coupon = new WC_Coupon($coupon_title);

                                    $discount_type = $coupon->discount_type;

                                    if ( isset( $discount_type ) && $discount_type ) {
                                        $discount_type = sprintf( __( ' ( Type: %s )', SA_Smart_Offers::$text_domain ), $all_discount_types[$discount_type] );
                                    }

                                    $json_coupons[ $coupon_title ] = $coupon_title . $discount_type;

                                }

                                echo esc_attr( json_encode( $json_coupons ) );

                            }

                        ?>" value="<?php echo implode( ',', array_keys( $json_coupons ) ); ?>" />
                    <?php } else { ?>
                        <select id="sa_coupon_title" name="sa_coupon_title[]" class="ajax_chosen_select_coupons" multiple="multiple" data-placeholder="<?php _e('Search for a coupon...', SA_Smart_Offers::$text_domain); ?>">
                            <?php
                                if ( !class_exists( 'WC_Coupon' ) ) {
                                    require_once( WP_PLUGIN_DIR . '/woocommerce/classes/class-wc-coupon.php' );
                                }

                                $all_discount_types = $sa_smart_offers->wc_get_coupon_types();

                                if ( $apply_coupon ) {
                                    $coupon_titles = explode(',', $apply_coupon);
                                    foreach ($coupon_titles as $coupon_title) {

                                        $coupon = new WC_Coupon($coupon_title);

                                        $discount_type = $coupon->discount_type;

                                        if (isset($discount_type) && $discount_type)
                                            $discount_type = ' ( Type: ' . $all_discount_types[$discount_type] . ' )';

                                        echo '<option value="' . $coupon_title . '" selected="selected">' . $coupon_title . $discount_type . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    <?php } ?>
                </fieldset><br/>
                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="accepted_offer_ids" id="accepted_offer_ids" <?php if (!empty($accepted_offer_ids) && $accepted_offer_ids == true) echo 'checked="checked"'; ?> value="accepted_offer_ids">
                    <label class="accept_input_checkboxes" id="accepted_offer_ids" for="accepted_offer_ids"><?php _e('Accept & Show Another Offer', SA_Smart_Offers::$text_domain); ?></label>
                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                        <input type="hidden" class="so-offer-search" style="width: 50%;" id="accept_offer_ids" name="accept_offer_ids" data-placeholder="<?php _e( 'Search for an offer&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_offers" data-multiple="true" data-selected="<?php

                            $json_accept_offers = array();

                            if ( ! empty( $offer_ids_on_accept ) ) {
                                $offer_accept_id = array_filter( array_map( 'absint', explode( ',', $offer_ids_on_accept ) ) );
                                
                                if ( ! empty( $offer_accept_id ) ) {
                                    
                                    foreach ( $offer_accept_id as $id ) {
                                        $title = get_the_title( $id );
                                        if ( ! empty( $title ) ) {
                                            $json_accept_offers[ $id ] = $title;   
                                        }
                                    }

                                    echo esc_attr( json_encode( $json_accept_offers ) );
                                } else {
                                    echo '';
                                }

                            }

                        ?>" value="<?php echo ( ! empty( $json_accept_offers ) ) ? implode( ',', array_keys( $json_accept_offers ) ): ''; ?>" />
                    <?php } else { ?>
                        <select id="accept_offer_ids" name="accept_offer_ids[]" class="ajax_chosen_select_offers" multiple="multiple" data-placeholder="<?php _e('Search for an offer...', SA_Smart_Offers::$text_domain); ?>" style="width: 50%; padding: 2px; line-height: 28px; height: 28px; vertical-align: middle;">
                            <?php
                                
                                if ( ! empty( $offer_ids_on_accept ) ) {

                                    if ( $offer_accept_id ) {
                                        $offer_accept_id = explode(',', $offer_accept_id);
                                        
                                        foreach ( $offer_accept_id as $id ) {
                                            $title = get_the_title( $id );
                                            if ( ! $title ) {
                                                echo '<option value="" ></option>';
                                            } else {
                                                echo '<option value="' . $id . '" selected="selected">' . $title . '</option>';
                                            }
                                        }

                                    }
                                }
                            ?>
                        </select>
                    <?php } ?>
                    <img class="help_tip" data-tip='<?php _e('Offer to be shown if this offer is accepted. If multiple offers are chosen, one will be shown based on your settings.', SA_Smart_Offers::$text_domain); ?>' src="<?php echo $sa_smart_offers->global_wc()->plugin_url(); ?>/assets/images/help.png" />
                </fieldset>
                <br />

                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="sa_redirect_to_url" id="sa_redirect_to_url" <?php if (isset($sa_redirect_to_url) && $sa_redirect_to_url == true) echo 'checked="checked"'; ?> value="sa_redirect_to_url">
                    <label class="accept_input_checkboxes" id="sa_redirect_to_url" for="sa_redirect_to_url"><?php _e('Redirect to a URL', SA_Smart_Offers::$text_domain); ?></label>
                    <input type='text' placeholder="<?php _e("https://www.google.co.in", SA_Smart_Offers::$text_domain); ?>" name='accept_redirect_url' id='accept_redirect_url' 
                        value='<?php 
                                    if (isset($action_on_accept['sa_redirect_to_url'])) {
                                        echo $action_on_accept['sa_redirect_to_url'];
                                    } 
                                ?>' />
                </fieldset><br/>
                <fieldset>
                    <input type="checkbox" class="accept_input_checkboxes" name="sa_buy_now" id="buy_now" <?php if (isset($buy_now) && $buy_now == true) echo 'checked="checked"'; ?> value="buy_now">
                    <label class="accept_input_checkboxes" id="buy_now" for="buy_now"><?php _e('Instantly Checkout with "Buy Now" plugin', SA_Smart_Offers::$text_domain); ?></label>
                </fieldset>
                <br/>
            </div>
            <div id="so_when_offer_skipped" class="panel woocommerce_options_panel">

                <?php
                    $offer_denied_option = get_post_meta($post->ID, 'sa_smart_offer_if_denied', true);
                    if ( empty( $offer_denied_option ) ) {
                        $offer_denied_option = 'order_page';
                    }
                    $url = get_post_meta($post->ID, 'url', true);
                ?>
                <h3><?php echo __( 'Actions to take when offer is skipped', SA_Smart_Offers::$text_domain ); ?></h3>
                <br/>

                <fieldset>
                    <input type="radio" class='skip_options_radio' name="sa_smart_offer_if_denied" id="order_page" value="order_page" <?php if ($offer_denied_option == "order_page") echo 'checked="checked"'; ?> />
                    <label class="skip_options_radio" id="order_page" for="order_page"><?php _e('Skip only - Hide this offer', SA_Smart_Offers::$text_domain); ?></label>
                </fieldset>
                <br />
                <fieldset>
                    <input type="radio" class='skip_options_radio' name="sa_smart_offer_if_denied" id="offer_page" value="offer_page" <?php if ($offer_denied_option == "offer_page") echo 'checked="checked"'; ?> />
                    <label class="skip_options_radio" for="offer_page"><?php _e('Skip & Show Another Offer', SA_Smart_Offers::$text_domain); ?></label>
                    <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                        <input type="hidden" class="so-offer-search" style="width: 50%;" id="offer_ids" name="offer_ids" data-placeholder="<?php _e( 'Search for an offer&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_offers" data-multiple="true" data-selected="<?php

                            $json_offers = array();

                            if ( $offer_denied_option == "offer_page" ) {

                                $offer_id = get_post_meta( $post->ID, 'url', true );

                                if ( ! empty( $offer_id ) ) {
                                    $offer_id = array_filter( array_map( 'absint', explode( ',', $offer_id ) ) );
                                    foreach ( $offer_id as $id ) {
                                        $title = get_the_title( $id );
                                        if ( ! empty( $title ) ) {
                                            $json_offers[ $id ] = $title;   
                                        }
                                    }
                                    echo esc_attr( json_encode( $json_offers ) );
                                }

                            }

                        ?>" value="<?php echo implode( ',', array_keys( $json_offers ) ); ?>" />
                    <?php } else { ?>
                        <select id="offer_ids" name="offer_ids[]" class="ajax_chosen_select_offers" multiple="multiple" data-placeholder="<?php _e('Search for an offer...', SA_Smart_Offers::$text_domain); ?>" style="width: 50%; padding: 2px; line-height: 28px; height: 28px; vertical-align: middle;">
                            <?php
                                if ( $offer_denied_option == "offer_page" ) {
                                    $offer_id = get_post_meta( $post->ID, 'url', true );
                                    if ( $offer_id ) {
                                        $offer_id = explode(',', $offer_id);
                                        foreach ( $offer_id as $id ) {
                                            $title = get_the_title( $id );
                                            if ( ! $title ) {
                                                echo '<option value="" ></option>';
                                            } else {
                                                echo '<option value="' . $id . '" selected="selected">' . $title . '</option>';
                                            }
                                        }
                                    }
                                }
                            ?>
                        </select>
                    <?php } ?>
                    <img class="help_tip" data-tip='<?php _e('Offer to be shown if this offer is skipped. If multiple offers are chosen, one will be shown based on your settings.', SA_Smart_Offers::$text_domain); ?>' src="<?php echo $sa_smart_offers->global_wc()->plugin_url(); ?>/assets/images/help.png" />
                </fieldset>
                <br />
                <fieldset>
                    <input type="radio" class='skip_options_radio' name="sa_smart_offer_if_denied" id="particular_page" value="particular_page" <?php if ($offer_denied_option == "particular_page") echo 'checked="checked"'; ?> />
                    <label class="skip_options_radio" for="particular_page"><?php _e('Skip & Redirect to', SA_Smart_Offers::$text_domain); ?></label>
                    <?php
                        $args = array('selected' => $url);
                        wp_dropdown_pages($args);
                    ?>
                </fieldset>
                <br />
                <fieldset>
                    <input type="radio" class="skip_options_radio" name="sa_smart_offer_if_denied" id="url" value="url" <?php if ($offer_denied_option == "url") echo 'checked="checked"'; ?> />
                    <label class="skip_options_radio" for="url"><?php _e('Skip & Redirect to URL', SA_Smart_Offers::$text_domain); ?></label>
                    <?php $value = ($offer_denied_option == "url") ? $url : ''; ?>
                    <input type='text' name='text_url' id='text_url' value='<?php echo $value; ?>' />
                </fieldset>
                <p class="form-field">
                <fieldset>
                    <input type="checkbox" class="checkbox" id="sa_smart_offer_if_denied_skip_permanently" name="sa_smart_offer_if_denied_skip_permanently" class="checkbox" value="yes" <?php if (get_post_meta($post->ID, 'sa_smart_offer_if_denied_skip_permanently', true) == "yes") echo 'checked="checked"'; ?>>
                    <?php _e('<strong>Hide From This User</strong> - Never show this offer to this customer again if skipped once', SA_Smart_Offers::$text_domain); ?>
                </fieldset>
                </p>
                </div>
                    <?php
                }

                /**
                * Search for offers and return json
                *
                * @access public
                * @return void
                * @see WC_AJAX::woocommerce_json_search_offers()
                */
                function woocommerce_json_search_offers($x = '', $post_types = array('smart_offers')) {

                    check_ajax_referer('search-offers', 'security');

                    $term = (string) urldecode(stripslashes(strip_tags($_GET ['term'])));

                    if (empty($term))
                        die();

                    $args = array('post_type' => $post_types, 'post_status' => 'publish', 'posts_per_page' => - 1, 'meta_query' => array(array('key' => 'offer_title', 'value' => $term, 'compare' => 'LIKE')), 'fields' => 'ids');

                    $posts = get_posts($args);

                    $found_offers = array();

                    if ($posts)
                        foreach ($posts as $post) {
                            $found_offers [$post] = get_the_title($post);
                        }

                    echo json_encode($found_offers);

                    die();
                }

                /**
                * Search for coupons and return json
                *
                * @access public
                * @return void
                * @see WC_AJAX::woocommerce_json_search_coupons()
                */
                function woocommerce_json_search_coupons($x = '', $post_types = array('shop_coupon')) {
                    global $wpdb, $sa_smart_offers;

                    check_ajax_referer('search-coupons', 'security');

                    $term = (string) urldecode(stripslashes(strip_tags($_GET['term'])));

                    if (empty($term))
                        die();
                    
                    $all_discount_types = $sa_smart_offers->wc_get_coupon_types();
                    $search_coupons_args = array(
                                                    'post_type' => $post_types,
                                                    'post_status' => 'publish',
                                                    'posts_per_page' => -1,
                                                    's' => $term,
                                                    'fields' => 'ids'
                                                );
                    $found_coupon_ids = new WP_Query( $search_coupons_args );
                    $found_coupons = array();
                    if ( $found_coupon_ids->post_count > 0 ) {
                        foreach ( $found_coupon_ids->posts as $coupon_id ) {
                            $discount_type = get_post_meta( $coupon_id, 'discount_type', true );
                            if ( ! empty ( $all_discount_types[$discount_type] ) ) {
                                $discount_type = sprintf( __( ' (Type: %s)', SA_Smart_Offers::$text_domain ), $all_discount_types[$discount_type] );
                                $found_coupons[get_the_title($coupon_id)] = get_the_title($coupon_id) . $discount_type;
                            }
                        }
                    }
                    echo json_encode($found_coupons);

                    die();
                }

                /**
                * Search for categories and return json
                *
                * @access public
                * @return void
                * @see WC_AJAX::woocommerce_json_search_prod_category()
                */
                function woocommerce_json_search_prod_category($x = '', $category = array('product_cat')) {

                    check_ajax_referer('so-search-product-category', 'security');

                    $term = (string) urldecode(stripslashes(strip_tags($_GET ['term'])));

                    if (empty($term))
                        die();

                    $args = array(
                        'search' => $term,
                        'hide_empty' => 0
                    );

                    $get_category_by_name = get_terms('product_cat', $args);

                    $found_category = array();

                    if ($get_category_by_name) {
                        foreach ($get_category_by_name as $term) {
                            $found_category[$term->term_id] = $term->name;
                        }
                    }

                    echo json_encode($found_category);

                    die();
                }

                /**
                * Search for simple products, variations and return json
                *
                * @access public
                * @return void
                * @see WC_AJAX::woocommerce_json_search_prod_category()
                */
                function woocommerce_json_search_products_and_only_variations($x = '', $post_types = array('product', 'product_variation')) {

                    check_ajax_referer('search-products-and-only-variations', 'security');

                    global $sa_smart_offers;

                    $term = (string) urldecode(stripslashes(strip_tags($_GET['term'])));

                    if (empty($term))
                        die();

                    if (is_numeric($term)) {

                        $args = array(
                            'post_type' => $post_types,
                            'post_status' => array("publish", "private"),
                            'posts_per_page' => -1,
                            'post__in' => array(0, $term),
                            'fields' => 'ids'
                        );

                        $args2 = array(
                            'post_type' => $post_types,
                            'post_status' => array("publish", "private"),
                            'posts_per_page' => -1,
                            'post_parent' => $term,
                            'fields' => 'ids'
                        );

                        $args3 = array(
                            'post_type' => $post_types,
                            'post_status' => array("publish", "private"),
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_sku',
                                    'value' => $term,
                                    'compare' => 'LIKE'
                                )
                            ),
                            'fields' => 'ids'
                        );

                        $posts = array_unique(array_merge(get_posts($args), get_posts($args2), get_posts($args3)));
                    } else {

                        $args = array(
                            'post_type' => $post_types,
                            'post_status' => array("publish", "private"),
                            'posts_per_page' => -1,
                            's' => $term,
                            'fields' => 'ids'
                        );

                        $args2 = array(
                            'post_type' => $post_types,
                            'post_status' => array("publish", "private"),
                            'posts_per_page' => -1,
                            'meta_query' => array(
                                array(
                                    'key' => '_sku',
                                    'value' => $term,
                                    'compare' => 'LIKE'
                                )
                            ),
                            'fields' => 'ids'
                        );

                        $posts = array_unique(array_merge(get_posts($args, ARRAY_A), get_posts($args2, ARRAY_A)));
                    }

                    $found_products = array();

                    if ($posts) {

                        foreach ($posts as $post) {

                            $post_type = get_post_type($post);
                            $product_type = wp_get_object_terms($post, 'product_type', array('fields' => 'slugs'));

                            if ($post_type == "product" && $product_type[0] == "variable") {
                                continue;
                            } else {

                                // To show the name of the products according to WC version
                                if ($sa_smart_offers->is_wc_gte_20()) {
                                    $product = $sa_smart_offers->get_product($post);

                                    $found_products[$post] = $sa_smart_offers->get_formatted_product_name( $product );
                                } else {

                                    $SKU = get_post_meta($post, '_sku', true);

                                    if (isset($SKU) && $SKU)
                                        $SKU = ' (SKU: ' . $SKU . ')';

                                    $found_products[$post] = get_the_title($post) . ' &ndash; #' . $post . $SKU;
                                }
                            }
                        }
                    }

                    echo json_encode($found_products);

                    die();
                }

                /**
                * 
                * Add default content in offer description
                */
                function so_add_default_content($content) {
                    global $post_type;

                    if (isset($post_type)) {
                        if ($post_type == "smart_offers") {
                            $content = '
<h1>Offer Heading</h1>

<p>Offer Description</p>

<div class="so_accept"><a href=[so_acceptlink]>Yes, Add to Cart</a></div>
<div class="so_skip"><a href=[so_skiplink]>No, Skip this offer</a></div>
';

                            return $content;
                        }
                    }
                }

                /**
                * Add custom message for SO
                */
                public function so_add_custom_messages($messages) {
                    $post_ID = isset($post_ID) ? (int) $post_ID : 0;
                    $messages ['smart_offers'] [1] = sprintf(__('Offer updated successfully.'));
                    $messages ['smart_offers'] [2] = sprintf(__('<strong>Warning:</strong> Offer description does not include accept / skip links. <a id="missing_shortcode" href="">Click here to fix it automatically.</a>'));
                    return $messages;
                }

                /**
                * Add [so_product_variant] shortcode in offer description if not present
                */
                function add_shortcode_in_post_content($data) {

                    // To execute this only if post type is smart_offers and also if POST contains target_product_ids
                    if ($data['post_type'] != "smart_offers")
                        return $data;

                    global $sa_smart_offers;

                    if (isset($_POST ['target_product_ids']) && isset($_POST ['content'])) {

                        $offered_product_id = ( $sa_smart_offers->is_wc_gte_23() ) ? $_POST ['target_product_ids'] : implode( ',', $_POST ['target_product_ids'] );
                        $offered_prod_instance = $sa_smart_offers->get_product($offered_product_id);
                        $sc_position = strpos($_POST ['content'], '[so_product_variants');
                        $add_sc = false;

                        if ($sa_smart_offers->is_wc_gte_20()) {

                            if ($offered_prod_instance->product_type == 'variable' && ( $sc_position === false )) {
                                $add_sc = true;
                            }
                        } else {

                            if ($offered_prod_instance->product_type == 'variable' && !isset($offered_prod_instance->variation_id) && ( $sc_position === false )) {
                                $add_sc = true;
                            }
                        }

                        if ($add_sc == true) {
                            $data['post_content'] = "[so_product_variants]" . $_POST ['content'];
                            add_filter('redirect_post_location', array(&$this, 'my_redirect_post_location_filter'));
                        }
                    }

                    return $data;
                }

                /**
                * Add redirect parameter after adding shortcode
                */
                function my_redirect_post_location_filter($location) {
                    remove_filter('redirect_post_location', __FUNCTION__);
                    $location = add_query_arg('show_sc_msg', true, $location);
                    return $location;
                }

                /**
                * Add additional CSS
                */
                function so_admin_style() {
                    global $typenow, $sa_smart_offers,$post_type, $post;
                    if ($typenow == 'smart_offers') {
                        wp_enqueue_style('woocommerce_admin_styles', $sa_smart_offers->global_wc()->plugin_url() . '/assets/css/admin.css');
                        wp_enqueue_style('so_admin_styles', plugins_url(SMART_OFFERS) . '/assets/css/admin.css');
                        wp_enqueue_style('jquery-ui-style', (is_ssl()) ? 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' : 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
                        
                        if ($sa_smart_offers->is_wc_gte_20()) {
                            
                            $css = "img.help_tip { 
                                        width:16px;height=16px; 
                                    }
                                    
                                    div#so_offer_as .form-table th { 
                                        width: 135px;
                                    }
                                    
                                    div#so_where_to_offer .description, div#so_offer_as .description {
                                        display: inline;
                                        margin-left: 5px;
                                        
                                    }
                                    
                                    div#so_offer_as label{
                                        width: 175px;
                                    }
                                    
                                    div#so_offer_as input[type=radio]{
                                       margin-top: 0px; 
                                       margin-right: 0px;
                                    }
                                    
                                    div.woo_offer_rule select.role {
                                        width: 18%;

                                    }
                            ";
                            
                            wp_add_inline_style( 'so_admin_styles', $css );
                        } 

                        if ( $post->post_status == 'auto-draft' || $post->post_status == 'draft' ) {
                        	$style_to_hide_view_btn = "#post-preview, #view-post-btn{display: none;}";
                        } else {
                        	$style_to_hide_view_btn = "#view-post-btn{display: none;}";
                        }
                        wp_add_inline_style( 'so_admin_styles', $style_to_hide_view_btn );
                    }
                    
                }

            }

            return new SO_Admin_Offer();
        }


