<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Dashboard_Widget')) {

    Class SO_Admin_Dashboard_Widget {

        function __construct() {
            add_action('wp_dashboard_setup', array($this, 'init_dashboard'), 10);
        }

        /**
    	* Init dashboard widgets
        */
        function init_dashboard() {
            wp_add_dashboard_widget('smart_offers_dashboard_widget', __('Smart Offers', SA_Smart_Offers::$text_domain), array($this, 'smart_offers_stats'));
        }

        /**
        * Show SO statistics
        */
        function smart_offers_stats() {
            global $wpdb, $sa_smart_offers;

            if ($sa_smart_offers->is_wc_gte_21()) {
                wp_enqueue_style('woocommerce_admin_styles', $sa_smart_offers->global_wc()->plugin_url() . '/assets/css/admin.css');
            }

            $wpdb->query("SET SESSION group_concat_max_len=999999");

            $offers_count_args = array(
                                        'post_type' => 'smart_offers',
                                        'fields' => 'ids',
                                        'nopaging' => true,
                                        'post_status' => array( 'publish', 'private' ),
                                        'meta_query' => array(
                                                            array(
                                                                    'key' => 'so_accept_skip_counter'
                                                                )
                                                        )
                                    );

            $query_results_for_offers_count = new WP_Query( $offers_count_args );
            
            $accept_count = 0;
            $skip_count = 0;
            $total_count = 0;

            if ( $query_results_for_offers_count->post_count > 0 ) {

                foreach ( $query_results_for_offers_count->posts as $post_id ) {
                    $result = get_post_meta( $post_id, 'so_accept_skip_counter', true );
                    foreach ($result as $key => $value) {
                        if ($key == "accepted") {
                            $accept_count += $value;
                        }
                        if ($key == "skipped") {
                            $skip_count += $value;
                        }
                        if ($key == "offer_shown") {
                            $total_count += $value;
                        }
                    }
                }

            }

            if ( $sa_smart_offers->is_wc_gte_22() ) {

                $offers_sale_args = array(
                                            'post_type' => 'shop_order',
                                            'fields' => 'ids',
                                            'nopaging' => true,
                                            'post_status' => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
                                            'meta_query' => array(
                                                                array(
                                                                        'key' => 'smart_offers_meta_data'
                                                                    )
                                                            )                                            
                                        );
                $offers_sale_order_ids = new WP_Query( $offers_sale_args );

            } else {

                $offers_sale_args = array(
                                            'post_type' => 'shop_order',
                                            'fields' => 'ids',
                                            'nopaging' => true,
                                            'post_status' => 'publish',
                                            'meta_query' => array(
                                                                array(
                                                                        'key' => 'smart_offers_meta_data'
                                                                    )
                                                            ),
                                            'tax_query' => array(
                                                                array(
                                                                        'taxonomy' => 'shop_order_status',
                                                                        'field' => 'slug',
                                                                        'terms' => array( 'completed', 'processing', 'on-hold' )
                                                                    )
                                                            )                                           
                                        );

                $offers_sale_order_ids = new WP_Query( $offers_sale_args );

            }

            $offers_paid_through = 0;
            $total_sale = 0;

            if ( $offers_sale_order_ids->post_count > 0 ) {
                foreach ( $offers_sale_order_ids->posts as $post_id ) {
                    $result = get_post_meta( $post_id, 'smart_offers_meta_data', true );
                    $offers_paid_through = $offers_paid_through + count( $result );
                    foreach ( $result as $key => $value ) {
                        $total_sale += $value ['offered_price'];
                    }
                }
            }

            $conversion_rate = ($total_count != 0) ? ($offers_paid_through / $total_count) * 100 : 0;

            $stats = '<ul class="woocommerce_stats">';
            $stats .= '<li style="width: 59%; overflow: hidden"><strong>' . $sa_smart_offers->wc_price($total_sale) . '</strong><center> ' . __('Revenue from Offers', SA_Smart_Offers::$text_domain) . '</center></li>';
            $stats .= '<li style="width: 31%; overflow: hidden"><strong>' . $sa_smart_offers->wc_format_decimal($conversion_rate) . '%' . '</strong> ' . __('Conversion Rate', SA_Smart_Offers::$text_domain) . '</li>';
            $stats .= '</ul>';
            $stats .= '<ul class="woocommerce_stats">';
            $stats .= '<li style="width: 21%"><strong>' . $total_count . '</strong> ' . __('Offers Seen', SA_Smart_Offers::$text_domain) . '</li>';
            $stats .= '<li style="width: 21%"><strong>' . $skip_count . '</strong> ' . __('Skipped', SA_Smart_Offers::$text_domain) . '</li>';
            $stats .= '<li style="width: 21%"><strong>' . $accept_count . '</strong> ' . __('Accepted', SA_Smart_Offers::$text_domain) . '</li>';
            $stats .= '<li style="width: 21%"><strong>' . $offers_paid_through . '</strong> ' . __('Paid Through', SA_Smart_Offers::$text_domain) . '</li>';
            $stats .= '</ul>';

            echo $stats;
        }

    }
    return new SO_Admin_Dashboard_Widget();
}


