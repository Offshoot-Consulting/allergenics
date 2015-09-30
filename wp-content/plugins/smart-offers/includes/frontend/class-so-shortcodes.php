<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Shortcodes')) {

    Class SO_Shortcodes {

        function __construct() {
            global $sa_smart_offers;

            // Add shortcodes on different Wordpress & Woocommerce hooks
            add_action('wp_head', array(&$this, 'show_offer_on_home_page'));

            if ($sa_smart_offers->is_wc_gte_20()) {
                add_action('woocommerce_before_cart', array(&$this, 'to_show_offer_on_cart'));
            } else {
                add_action('woocommerce_before_cart_table', array(&$this, 'to_show_offer_on_cart'));
            }

            add_action('woocommerce_cart_is_empty', array(&$this, 'so_cart_empty'));
            add_action('woocommerce_before_checkout_form', array(&$this, 'to_show_offer_on_checkout'));
            add_action('woocommerce_before_my_account', array(&$this, 'to_show_offer_on_account'));
            add_action('woocommerce_thankyou', array(&$this, 'to_show_offer_on_thankyou'), 9);

            add_shortcode('so_show_offers', array(&$this, 'shortcode_for_showing_offers'));
            add_shortcode('so_acceptlink', array(&$this, 'shortcode_for_accept_link'));
            add_shortcode('so_skiplink', array(&$this, 'shortcode_for_skip_link'));
            add_shortcode('so_product_variants', array(&$this, 'shortcode_for_showing_product_variants'));
            add_shortcode('so_quantity', array(&$this, 'shortcode_for_showing_quantity'));
            add_shortcode('so_product_image', array(&$this, 'shortcode_for_showing_product_image'));
            add_shortcode('so_price', array(&$this, 'shortcode_for_showing_price'));
        }

        /**
    	 * Process and show offer on Home page as popup
    	 */
        function show_offer_on_home_page() {
            if (is_home() || is_front_page()) {
                do_shortcode("[so_show_offers display_as='popup']");
            }
        }

        /**
    	 * Process and show offer on cart page
    	 */
        function to_show_offer_on_cart() {
            do_shortcode("[so_show_offers]");
        }

        /**
    	 * Process and show offer on Cart empty template
    	 */
        function so_cart_empty() {
            $this->to_show_offer_on_cart();
        }

        /**
    	 * Process and show offer on Checkout page as popup
    	 */
        function to_show_offer_on_checkout() {
            do_shortcode("[so_show_offers]");
        }

        /**
    	 * Process and show offer on account page as popup
    	 */
        function to_show_offer_on_account() {
            do_shortcode("[so_show_offers]");
        }

        /**
    	 * Process and show offer on order received page as popup
    	 */
        function to_show_offer_on_thankyou($order_id) {
            do_shortcode("[so_show_offers]");
        }

        /**
    	 * Shortcode function for accept button.
    	 */
        function shortcode_for_accept_link($atts) {
            return $this->get_link($atts, 'accept');
        }

        /**
    	 * Shortcode function for skip button.
    	 */
        function shortcode_for_skip_link($atts) {
            return $this->get_link($atts, 'skip');
        }

        /**
    	 * return accept/skip link
    	 */
        function get_link($atts, $action) {

            if (isset($_GET ['preview']) && $_GET ['preview'] == 'true') {
                return home_url();
            }

            if (empty($atts)) {
                return;
            }

            extract(shortcode_atts(array(
                'offer_id' => '',
                'page_url' => '',
                'source'   => ''
                            ), $atts));

            $page_url = urldecode($page_url);

            $args = array( 'so_action' => $action, 'so_offer_id' => $offer_id );

            if ( ! empty( $source ) ) {
                $new_args = array( 'source' => $source );
                $args = array_merge( $args, $new_args );
            }

            $query_args = apply_filters( 'so_link_args', $args, $offer_id, $action );

            $skip_url = add_query_arg( $query_args, $page_url );

            return $skip_url;
        }

        /**
    	 * Shortcode to show product variants in Offer description
    	 */
        function shortcode_for_showing_product_variants($atts) {

            if (empty($atts)) {
                return;
            }

            global $sa_smart_offers;

            extract(shortcode_atts(array(
                'prod_id' => '',
                'offer_id' => '',
                'page' => '',
                'where_url' => '',
                'image' => 'yes'
                            ), $atts));

            if ($page == "cart_page" && !($sa_smart_offers->is_wc_gte_20())) {
                return;
            }

            if ( $page == 'post_checkout_page' ) {
                $source = 'so_post_checkout';
            } elseif ( $page == 'checkout_page' ) {
                $source = 'so_pre_checkout';
            } else {
                $source = '';
            }

            wp_enqueue_script('wc-add-to-cart-variation');

            $product = $sa_smart_offers->get_product($prod_id);
            $available_variations = $product->get_available_variations();
            $selected_attributes = $product->get_variation_default_attributes();

            foreach ($available_variations as $key => $value) {

                if ( ! empty( $value['attributes'] ) ) {
                    $found = 0;
                    foreach ( $value['attributes'] as $attr_key => $attr_value ) {
                        $attr_key = str_replace( 'attribute_', '', $attr_key );
                        if ( ! empty( $selected_attributes[ $attr_key ] ) && $selected_attributes[ $attr_key ] == $attr_value ) {
                            $found++;
                        }
                    }
                }

                $variation_id = $value['variation_id'];
                $prod_instance = $sa_smart_offers->get_product($variation_id);
                $sale_price = $prod_instance->get_sale_price();
                $price = $prod_instance->get_price();
                $so_offer = new SO_Offer();
                $offer_price = $so_offer->get_offer_price(array('offer_id' => $offer_id, 'prod_id' => $variation_id));
                if ( $sale_price != $offer_price ) {
                    $so_display_price_html = '<del>' . $prod_instance->get_price_html() . '</del> <ins>' . $sa_smart_offers->wc_price($offer_price) . '</ins>';
                } else {
                    $so_display_price_html = $prod_instance->get_price_html();
                }
                $available_variations[$key]['price_html'] = '<span class="price"> ' . __( 'Offer Price', SA_Smart_Offers::$text_domain ) . ': ' . $so_display_price_html . '</span>';
            }

            $attributes = $product->get_variation_attributes();

            if ($sa_smart_offers->is_wc_gte_20()) {

                $accept_link = do_shortcode("[so_acceptlink offer_id=" . $offer_id . " page_url=" . urlencode($where_url . "/") . " source=" . $source . "]");
                $accept_link = untrailingslashit( str_replace( "#038;", "&", $accept_link ) );

                $return_string = '<form action="' . $accept_link . '" class="variations_form cart" method="POST" id="so_addtocart_' . $offer_id . '" enctype="multipart/form-data" data-product_id="' . $prod_id . '" data-product_variations="' . esc_attr(json_encode($available_variations)) . '">';
                if ( $image == 'yes' ) {
                    $return_string .= do_shortcode("[so_product_image]");
                }
                $return_string .= '<table class="variations" cellspacing="0"><tbody>';
                $loop = 1;
                foreach ($attributes as $name => $options) {

                    $return_string .= '<tr>';
                    $return_string .= '<td class="label"><label for="' . sanitize_title($name) . '">' . $sa_smart_offers->wc_attribute_label($name) . '</label></td>';
                    $return_string .= '<td class="value"><select class="attribute_' . $loop . '" id="' . esc_attr(sanitize_title($name)) . '" name="attribute_' . sanitize_title($name) . '">';
                    $return_string .= '<option value="">' . __('Choose an option', SA_Smart_Offers::$text_domain) . '</option>';

                    if (is_array($options)) {

                        $selected_value = ( isset($selected_attributes[sanitize_title($name)]) ) ? $selected_attributes[sanitize_title($name)] : '';

                        if (taxonomy_exists($name)) {

                            $orderby = $sa_smart_offers->wc_attribute_orderby($name);

                            $args = array();
                            switch ($orderby) {
                                case 'name' :
                                    $args = array('orderby' => 'name', 'hide_empty' => false, 'menu_order' => false);
                                    break;
                                case 'id' :
                                    $args = array('orderby' => 'id', 'order' => 'ASC', 'menu_order' => false);
                                    break;
                                case 'menu_order' :
                                    $args = array('menu_order' => 'ASC');
                                    break;
                            }

                            $terms = get_terms($name, $args);

                            foreach ($terms as $term) {
                                if (!in_array($term->slug, $options))
                                    continue;

                                $return_string .= '<option value="' . esc_attr($term->slug) . '" ' . selected($selected_value, $term->slug, false) . '>' . apply_filters('woocommerce_variation_option_name', $term->name) . '</option>';
                            }
                        } else {

                            foreach ($options as $option) {
                                $return_string .= '<option value="' . esc_attr(sanitize_title($option)) . '" ' . selected(sanitize_title($selected_value), sanitize_title($option), false) . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</option>';
                            }
                        }
                    }

                    $return_string .= '</select></td>';
                    $return_string .= '</tr>';
                    $loop++;
                }
            } else {

                $accept_link = do_shortcode("[so_acceptlink offer_id=" . $offer_id . " page_url=" . urlencode($where_url . "/") . " source=" . $source . "]");
                $accept_link = untrailingslashit( str_replace( "#038;", "&", $accept_link ) );

                $return_string = '<script type="text/javascript">';
                $return_string .= 'var product_variations_' . $prod_id . '=' . json_encode($available_variations) . '</script>';
                $return_string .= '<form action="' . $accept_link . '" class="variations_form cart" method="POST" id="so_addtocart_' . $offer_id . '" enctype="multipart/form-data" data-product_id="' . $prod_id . '">';
                $return_string .= '<table class="variations" cellspacing="0"><tbody>';
                $loop = 1;
                foreach ($attributes as $name => $options) {

                    $return_string .= '<tr><td class="label"><label for="' . sanitize_title($name) . '">' . $sa_smart_offers->wc_attribute_label($name) . '</label></td>';
                    $return_string .= '<td class="value"><select class="attribute_' . $loop . '" id="' . esc_attr(sanitize_title($name)) . '" name="attribute_' . sanitize_title($name) . '">';
                    $return_string .= '<option value="">' . __('Choose an option', SA_Smart_Offers::$text_domain) . '</option>';

                    if (is_array($options)) {

                        $selected_value = ( isset($selected_attributes[sanitize_title($name)]) ) ? $selected_attributes[sanitize_title($name)] : '';

                        if (taxonomy_exists(sanitize_title($name))) {

                            $terms = get_terms(sanitize_title($name), array('menu_order' => 'ASC'));

                            foreach ($terms as $term) {

                                if (!in_array($term->slug, $options))
                                    continue;
                                $return_string .= '<option value="' . $term->slug . '" ' . selected($selected_value, $term->slug, false) . '>' . apply_filters('woocommerce_variation_option_name', $term->name) . '</option>';
                            }
                        } else {

                            foreach ($options as $option) {
                                $return_string .= '<option value="' . esc_attr(sanitize_title($option)) . '" ' . selected(sanitize_title($selected_value), sanitize_title($option), false) . '>' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</option>';
                            }
                        }
                    }

                    $return_string .= '</select></td></tr>';
                    $loop++;
                }
            }

            $return_string .= '</tbody></table>';
            $return_string .= '<input type="hidden" id="parent_prod_id" name="parent_prod_id" value="' . $prod_id . '">';
            $return_string .= '<input type="hidden" name="variation_id" value="" />';
            $return_string .= '<div class="single_variation_wrap" style="display:none;"><div class="single_variation"></div></div></form>';

            return $return_string;
        }

        /**
         * Shortcode to allow changing product quantity in the offer
         */
        function shortcode_for_showing_quantity($atts) {

            extract(shortcode_atts(array(
                'value' => 1,
                'allow_change' => 'false',
                'min' => 1,
                'max' => '',
                'prod_id' => '',
                'offer_id' => '',
                'page' => '',
                'where_url' => ''
                            ), $atts));

            global $sa_smart_offers;

            if ($page == "cart_page" && !($sa_smart_offers->is_wc_gte_20()))
                return;

            if ($allow_change == 'false') {
                $style = "display: none";
            }

            $accept_link = do_shortcode("[so_acceptlink offer_id=" . $offer_id . " page_url=" . urlencode($where_url . "/") . "]");
            $accept_link = untrailingslashit( str_replace( "#038;", "&", $accept_link ) );

            $html = '<form action="' . $accept_link . '" method="POST" id="so_qty_' . $offer_id . '"';
            if (!empty($style)) {
                $html .= 'style="' . $style . '"';
            }
            $html .= '>';

            $qty_params = array('input_value' => $value,
                'max_value' => $max,
                'min_value' => $min);

            $html .= woocommerce_quantity_input($qty_params, null, false);
            $html .= '</form>';

            return $html;
        }

        /**
    	 * Shortcode to show offer
    	 */
        function shortcode_for_showing_offers($atts) {
            extract(shortcode_atts(array(
                'display_as' => '',
                'offer_ids' => ''
                            ), $atts));

            $so_offers = new SO_Offers();
            $offers_data = $so_offers->get_offers($offer_ids);

            if (empty($offers_data)) {
                return;
            }

            $so_offer = new SO_Offer();
            $so_offer->prepare_offer($display_as, $offers_data);

        }

        /**
         * Shortcode to display product image
         */
        function shortcode_for_showing_product_image() {

            ob_start();

            global $post, $product, $sa_smart_offers;

            if ( $post->post_type != 'smart_offers' ) {
                return;
            }

            $current_post = $post;

            $target_product_id = get_post_meta( $post->ID, 'target_product_ids', true );

            $product = $sa_smart_offers->get_product( $target_product_id );

            $product_id = ( ! empty( $product->variation_id ) ) ? $product->variation_id : $product->id;

            if ( ! is_a( $product, 'WC_Product' ) ) {
                ob_clean();
                return;
            }

            if ( $sa_smart_offers->is_wc_gte_21() ) {

                $post = get_post( $product->id );
                query_posts( array( 'post_type' => $post->post_type, 'p' => $post->ID ) );
                wc_get_template( 'single-product/product-image.php', array( 'post' => $post, 'product' => $product ) );
                wp_reset_query();

                $post = $current_post;

            } else {

                ?>
                <div class="images so_product_image">

                    <?php
                        if ( has_post_thumbnail( $product_id ) ) {

                            $image_title = esc_attr( get_the_title( get_post_thumbnail_id( $product_id ) ) );
                            $image_link  = wp_get_attachment_url( get_post_thumbnail_id( $product_id ) );
                            $image       = get_the_post_thumbnail( $product_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
                                'title' => $image_title
                                ) );

                            $attachment_count = count( $product->get_gallery_attachment_ids() );

                            if ( $attachment_count > 0 ) {
                                $gallery = '[product-gallery]';
                            } else {
                                $gallery = '';
                            }

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_title, $image ), $product_id );

                        } else {

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $product_id );

                        }
                    ?>

                    <?php do_action( 'woocommerce_product_thumbnails' ); ?>

                </div>
                <?php
            }

            return ob_get_clean();

        }

        /**
        * Shortcode to show price in offer description (Simple Products)
        */
        function shortcode_for_showing_price() {

            global $post, $product, $sa_smart_offers;

            if ( $post->post_type != 'smart_offers' ) {
                return;
            }

            $target_product_id = get_post_meta( $post->ID, 'target_product_ids', true );

            $product = $sa_smart_offers->get_product( $target_product_id );

            $product_id = ( ! empty( $product->variation_id ) ) ? $product->variation_id : $product->id;

            if ( ! is_a( $product, 'WC_Product' ) ) {
                return;
            }

            $sale_price = $product->get_sale_price();
            
            $price = $product->get_price();
            $so_offer = new SO_Offer();
            $offer_price = $so_offer->get_offer_price( array( 'offer_id' => $post->ID, 'prod_id' => $target_product_id ) );
            
            if ( $sale_price != $offer_price ) {
                $so_display_price_html = '<del>' . $product->get_price_html() . '</del> <ins>' . $sa_smart_offers->wc_price($offer_price) . '</ins>';
            } else {
                $so_display_price_html = $product->get_price_html();
            }
            
            $price_content = '<span class="price"> ' . __( 'Offer Price', SA_Smart_Offers::$text_domain ) . ': ' . $so_display_price_html . '</span>';

            return $price_content;
        }

    }

}

return new SO_Shortcodes();