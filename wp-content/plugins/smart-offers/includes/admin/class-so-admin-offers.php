<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Offers')) {

    Class SO_Admin_Offers {

        function __construct() {
            add_action( 'manage_smart_offers_posts_custom_column', array($this, 'so_custom_columns' ), 2);
            add_action( 'admin_action_duplicate_offer', array( $this, 'so_duplicate_offer' ) );
            add_action( 'restrict_manage_posts', array( $this, 'so_restrict_manage_smart_offers' ), 20);
            add_action( 'admin_init', array( $this, 'so_reset_stats' ) );
            add_action( 'admin_notices', array( $this, 'so_reset_success_message' ) );
            add_action( 'load-edit.php', array( $this, 'so_edit_load' ) );

            add_action( 'admin_init', array( $this, 'so_check_hook_exists' ) );
            add_action( 'admin_notices', array( $this,'so_show_theme_notice' ) );
            add_action( 'admin_footer', array( $this, 'smart_offers_support_ticket_content' ) );
            
            add_filter( 'post_row_actions', array( $this, 'so_remove_view_link_row' ), 1, 2 );
            add_filter( 'manage_edit-smart_offers_columns', array( $this, 'so_edit_columns' ) );
            add_filter( 'manage_edit-smart_offers_sortable_columns', array( $this, 'so_sortable_columns' ) );
            add_filter( 'views_edit-smart_offers', array( $this, 'admin_new_button' ) );

            add_action( 'admin_print_scripts-edit.php', array( $this, 'smart_offers_admin_scripts' ) );
            add_action( 'bulk_edit_custom_box', array( $this, 'display_smart_offers_quick_bulk_edit_option' ), 10, 2 );
            add_action( 'quick_edit_custom_box', array( $this, 'display_smart_offers_quick_bulk_edit_option' ), 10, 2 );
            add_action( 'save_post', array( $this, 'process_quick_edit_smart_offers' ), 10, 2 );
            add_action( 'wp_ajax_process_bulk_edit_smart_offers', array( $this, 'process_bulk_edit_smart_offers' ) );
            add_action( 'wp_ajax_generate_embed_offer', array( $this, 'smart_offers_generate_embed_offer' ) );
        }
        
        /**
        * Add Support ticket content on SO edit page
        */
        function smart_offers_support_ticket_content() {
            global $pagenow, $typenow, $sa_smart_offers_upgrade;

            if ( $pagenow != 'edit.php' ) return;

            if ( $typenow != 'smart_offers') return;

            if ( ! method_exists( 'Store_Apps_Upgrade', 'support_ticket_content' ) ) return;

            $prefix = 'smart_offers';
            $sku = 'so';
            $plugin_data = get_plugin_data( __FILE__ );
            $license_key = get_site_option( $prefix.'_license_key' );
            $text_domain = 'smart_offers';

            Store_Apps_Upgrade::support_ticket_content( $prefix, $sku, $plugin_data, $license_key, $text_domain );
        }

        /**
	 * Define SO custom columns shown in admin.
	 * @param  string $column
	 */
        function so_custom_columns($columns) {
            global $post, $sa_smart_offers;

            $so_accept_skip_counter = get_post_meta($post->ID, 'so_accept_skip_counter', true);

            $offered_products = (get_post_meta($post->ID, 'target_product_ids', true)) ? explode(',', get_post_meta($post->ID, 'target_product_ids', true)) : array();
            $offer_seen = (isset($so_accept_skip_counter ['offer_shown'])) ? $so_accept_skip_counter ['offer_shown'] : 0;
            $accepted = (isset($so_accept_skip_counter ['accepted'])) ? $so_accept_skip_counter ['accepted'] : 0;
            $skipped = (isset($so_accept_skip_counter ['skipped'])) ? $so_accept_skip_counter ['skipped'] : 0;
            $count_of_orders = get_post_meta($post->ID, 'so_order_count', true);
            $count_of_orders_having_offers = ($count_of_orders) ? $count_of_orders ['order_count'] : 0;

            $conversion_rate = ($offer_seen != 0) ? ($count_of_orders_having_offers / $offer_seen) * 100 : 0;

            update_post_meta($post->ID, 'so_conversion_rate', $sa_smart_offers->wc_format_decimal($conversion_rate));

            switch ($columns) {
                case "offered_products" :
                    if (sizeof($offered_products) > 0) {
                        $product = $sa_smart_offers->get_product(implode(', ', $offered_products));
                        $title = $sa_smart_offers->get_formatted_product_name($product);
                        echo $title;
                    }
                    else
                        echo '&ndash;';
                    break;
                case "quick_stats" :
                    echo 'Seen: ' . $offer_seen . ', Skipped: ' . $skipped . ', Accepted: ' . $accepted . ', Paid: ' . $count_of_orders_having_offers;
                    break;
                case "conversion_rate" :
                    echo $sa_smart_offers->wc_format_decimal($conversion_rate) . '%';
                    break;
            }
        }

        /**
	 * Duplicate a offer action.
	 */
        function so_duplicate_offer() {

            if (!( isset($_GET['post']) || isset($_POST['post']) || ( isset($_REQUEST['action']) && 'duplicate_post_save_as_new_page' == $_REQUEST['action'] ) )) {
                wp_die(__('No offer to duplicate has been supplied!', SA_Smart_Offers::$text_domain));
            }

            // Get the original page
            $id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
            check_admin_referer('woocommerce-duplicate-offer_' . $id);
            $post = $this->sa_smart_offers_get_offer_to_duplicate($id);

            if (isset($post) && $post != null) {
                $new_id = $this->sa_smart_offers_duplicate_from_offer($post);

                // If you have written a plugin which uses non-WP database tables to save
                // information about a page you can hook this action to dupe that data.
                do_action('woocommerce_duplicate_offer', $new_id, $post);

                // Redirect to the edit screen for the new draft page
                wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_id));
                exit;
            } else {
                wp_die(__('Offer creation failed, could not find original product:', SA_Smart_Offers::$text_domain) . ' ' . $id);
            }
        }

        /**
	 * Get a offer from the database to duplicate
     *
	 * @access public
	 * @param mixed $id
	 * @return WP_Post|bool
	 * @see duplicate_product
	 */
        function sa_smart_offers_get_offer_to_duplicate($id) {
            global $wpdb;

            $smart_offers_args = array(
                                        'p' => $id,
                                        'post_type' => 'smart_offers',
                                        'nopaging' => true,
                                        'post_status' => array( 'publish', 'draft', 'pending' )
                                    );
            $smart_offers_query_results = new WP_Query( $smart_offers_args );

            if ( $smart_offers_query_results->post_count > 0 ) {
                return $smart_offers_query_results->post;
            }

            return null;
            
        }

    /**
	 * Function to create the duplicate of the offer.
	 *
	 * @access public
	 * @param mixed $post
	 * @param int $parent (default: 0)
	 * @param string $post_status (default: '')
	 * @return int
	 */
        function sa_smart_offers_duplicate_from_offer($post, $parent = 0, $post_status = '') {
            global $wpdb;

            $new_post_author = wp_get_current_user();
            $new_post_date = current_time('mysql');
            $new_post_date_gmt = get_gmt_from_date($new_post_date);

            if ($parent > 0) {
                $post_parent = $parent;
                $post_status = $post_status ? $post_status : 'publish';
                $suffix = '';
            } else {
                $post_parent = $post->post_parent;
                $post_status = $post_status ? $post_status : 'draft';
                $suffix = __("(Duplicate)", SA_Smart_Offers::$text_domain);
            }

            $new_post_type = $post->post_type;
            $post_content = str_replace("'", "''", $post->post_content);
            $post_content_filtered = str_replace("'", "''", $post->post_content_filtered);
            $post_excerpt = str_replace("'", "''", $post->post_excerpt);
            $post_title = str_replace("'", "''", $post->post_title) . $suffix;
            $post_name = str_replace("'", "''", $post->post_name);
            $comment_status = str_replace("'", "''", $post->comment_status);
            $ping_status = str_replace("'", "''", $post->ping_status);

            // Insert the new template in the post table
            $wpdb->insert(
                            $wpdb->posts,
                            array(
                                    'post_author'               => $new_post_author->ID,
                                    'post_date'                 => $new_post_date,
                                    'post_date_gmt'             => $new_post_date_gmt,
                                    'post_content'              => $post_content,
                                    'post_content_filtered'     => $post_content_filtered,
                                    'post_title'                => $post_title,
                                    'post_excerpt'              => $post_excerpt,
                                    'post_status'               => $post_status,
                                    'post_type'                 => $new_post_type,
                                    'comment_status'            => $comment_status,
                                    'ping_status'               => $ping_status,
                                    'post_password'             => $post->post_password,
                                    'to_ping'                   => $post->to_ping,
                                    'pinged'                    => $post->pinged,
                                    'post_modified'             => $new_post_date,
                                    'post_modified_gmt'         => $new_post_date_gmt,
                                    'post_parent'               => $post_parent,
                                    'menu_order'                => $post->menu_order,
                                    'post_mime_type'            => $post->post_mime_type
                                )
                        );

            $new_post_id = $wpdb->insert_id;

            // Copy the meta information
            $this->sa_smart_offers_duplicate_offer_post_meta($post->ID, $new_post_id);

            return $new_post_id;
        }

        /**
	 * Copy the meta information of a post to another post
	 *
	 * @access public
	 * @param mixed $id
	 * @param mixed $new_id
	 * @return void
	 */
        function sa_smart_offers_duplicate_offer_post_meta($id, $new_id) {
            global $wpdb;

            $post_meta_infos = get_post_meta( $id );

            if ( count( $post_meta_infos ) > 0 ) {
                foreach ( $post_meta_infos as $meta_key => $meta_value ) {

                    if ( $meta_key == "so_order_count" || $meta_key == "so_conversion_rate" || $meta_key == "so_accept_skip_counter" )
                        continue;
                    add_post_meta( $new_id, $meta_key, maybe_unserialize( $meta_value[0] ) );
                }
            }
        }


      /*
      * Generate embed offer
      */
        function smart_offers_generate_embed_offer(){
            global $sa_smart_offers;

            check_ajax_referer('so_generate_embed_offer', 'security');
            
            if(empty($_POST['post_id']) || $_POST['action'] != 'generate_embed_offer') return;

            $so_offer = new SO_Offer();

            $data = array();
            $offer_id = $_POST['post_id'];
            $where_url = get_option('siteurl');
            
            $offer_content = $so_offer->return_post_content($offer_id, $page = '', $where_url);
            $data['content'] = apply_filters('the_content', $offer_content);
            $button_style = get_option('so_accept_button_styles');

            if ( $button_style == 'smart_offers_custom_style_button' ) {
                $accept_css = get_option('so_css_for_accept');
                $style_for_accept_text = "";
            } else {
                $accept_css = get_option( $button_style );
                $style_for_accept_text = "div.so_accept a { text-decoration: none !important; color: white; }";
            }

            $skip_css = get_option('so_css_for_skip');
            $style_for_accept = "div.so_accept { $accept_css }";
            $style_for_skip = "div.so_skip { $skip_css }";

            $data['style'] = $style_for_accept . $style_for_skip . $style_for_accept_text;
                                   
            echo json_encode( $data );
            die();
        }

        /**
	 * Show reset quick stats button in admin
	 */
        function so_restrict_manage_smart_offers() {
            global $typenow, $pagenow;

            if ($typenow != 'smart_offers')     // show reset offers button only when post type is smart offers
                return;

            /* TO show Reset Quick Stats button on Smart Offers page */
            ?> 

            <script type="text/javascript">

                jQuery('input#reset_stats').live('click', function(e) {

                    var answer = confirm("<?php _e('Are you sure you want reset Quick Stats of Smart Offers?? It will clear data from Quick Stats and Conversion rate column & also Smart Offers widget on WordPress dashboard.'); ?>");

                    if (answer == false) {
                        e.preventDefault();
                    }

                });

            </script>
            <div class="alignright" style="margin-top: 1px;" >
                <input type="submit" name="reset_stats" id="reset_stats" class="button action" value="<?php _e('Reset All Quick Stats', SA_Smart_Offers::$text_domain); ?>" >
            </div>

            <?php
        }

        /**
	 * Action to reset the statistics
	 */
        function so_reset_stats() {
            global $wpdb, $typenow;

            if ( isset( $_GET['reset_stats'] ) ) {
                $all = true;
                $this->reset_quick_stats( array(), $all );
            }

            if (isset($_GET['so-theme-notice'])) {

                $dismiss_theme_notice = false;

                if ($_GET['so-theme-notice'] == 'add_shortcode') {

                    $page_ids = array('Cart' => woocommerce_get_page_id('cart'),
                        'Checkout' => woocommerce_get_page_id('checkout'),
                        'Order Received' => woocommerce_get_page_id('thanks'),
                        'My Account' => woocommerce_get_page_id('myaccount'));

                    $add_shortcode = get_option('so_theme_compatibility');

                    if (!empty($add_shortcode)) {

                        foreach ($add_shortcode as $page_name => $page_value) {

                            if ($page_value == true) {

                                $page_id = $page_ids[$page_name];
                                if ($page_id) {
                                    $page = get_post($page_id);
                                    $page_content = $page->post_content;
                                    $page_content = "[so_show_offers]" . $page_content;
//                                                      update
                                    $my_post = array();
                                    $my_post['ID'] = $page_id;
                                    $my_post['post_content'] = $page_content;
                                    wp_update_post($my_post);
                                }
                            }
                        }
                        $dismiss_theme_notice = true;
                    }
                } elseif ($_GET['so-theme-notice'] == 'dismiss_theme_notice') {
                    $dismiss_theme_notice = true;
                }

                if ($dismiss_theme_notice == true) {
                    update_option('so_theme_notice', "no");
                }
            }
        }

        /**
	 * Show admin messages
	 */
        function so_reset_success_message() {
            global $typenow, $pagenow, $post;

            if (!isset($_GET['so_reset_stats']) && !isset($_GET['show_sc_msg']))
                return;

            if (isset($_GET['so_reset_stats']) && $_GET['so_reset_stats'] == "success") {
                if ('edit.php' == $pagenow && 'smart_offers' == $typenow) {

                    echo '<div id="message" class="updated fade"><p>
                                                ' . sprintf(__('Smart Offers Statistics have been reset successfully', SA_Smart_Offers::$text_domain)) . '
                                        </p></div>';
                }
            }

            if (isset($_GET['show_sc_msg']) && $_GET['show_sc_msg'] == true) {

                if ('post.php' == $pagenow && 'smart_offers' == $typenow) {

                    echo '<div class="updated fade"><p>' . sprintf(__('Shortcode to show Product Variations is added in Offer Description.', SA_Smart_Offers::$text_domain)) . '</p></div>' . "\n";
                }
            }
        }

        /**
	 * Change the columns shown in admin.
	 */
        function so_edit_columns($columns) {

            $columns = array();

            $columns ["cb"] = "<input type=\"checkbox\" />";
            $columns ["title"] = __("Offer Title", SA_Smart_Offers::$text_domain);
            $columns ["offered_products"] = __("Product", SA_Smart_Offers::$text_domain);
            $columns ["quick_stats"] = __("Quick Stats", SA_Smart_Offers::$text_domain);
            $columns ["conversion_rate"] = __("Conversion Rate", SA_Smart_Offers::$text_domain);

            return $columns;
        }

        /**
	 * Make SO columns sortable
	 */
        function so_sortable_columns($columns) {
            $columns ["conversion_rate"] = "conversion_rate";
            $columns ["offered_products"] = "offered_products";
            return $columns;
        }

        /**
	 * Sort Offers orderby
	 */
        function so_edit_load() {
            add_filter('request', array(&$this, 'so_sort_converion_rate'));
            add_filter('request', array(&$this, 'so_sort_offered_products'));
        }
        
        /**
	 * Sort offers based on product
	 */
        function so_sort_offered_products($vars) {
            global $wp, $wp_query;

            /* Check if we're viewing the 'smart_offers' post type. */
            if (isset($vars ['post_type']) && 'smart_offers' == $vars ['post_type']) {

                /* Check if 'orderby' is set to 'offered_products'. */
                if (isset($vars ['orderby']) && 'offered_products' == $vars ['orderby']) {

                    /* Merge the query vars with our custom variables. */
                    $vars = array_merge($vars, array('meta_key' => 'target_product_ids'));
                }
            }

            return $vars;
        }

        /**
	 * Sort offers based on conversion rate
	 */
        function so_sort_converion_rate($vars) {
            global $wp, $wp_query;

            /* Check if we're viewing the 'smart_offers' post type. */
            if (isset($vars ['post_type']) && 'smart_offers' == $vars ['post_type']) {

                /* Check if 'orderby' is set to 'conversion_rate'. */
                if (isset($vars ['orderby']) && 'conversion_rate' == $vars ['orderby']) {

                    /* Merge the query vars with our custom variables. */
                    $vars = array_merge($vars, array('meta_key' => 'so_conversion_rate', 'orderby' => 'meta_value_num'));
                }
            }

            return $vars;
        }

        /**
	 * Add additional admin buttons in SO
	 */
        function admin_new_button($views) {
            global $menu, $submenu, $parent_file, $submenu_file, $post_type, $pagenow, $sa_smart_offers;

            if (isset($post_type)) {
                if ($post_type == "smart_offers") {
                    if ($sa_smart_offers->is_wc_gte_21()) {
                        $so_settings_url = '<a href=' . admin_url('admin.php?page=wc-settings&tab=smart_offers') . '>' . __( 'Settings and Styles', SA_Smart_Offers::$text_domain ) . '</a>';
                    } else {
                        $so_settings_url = '<a href=' . admin_url('admin.php?page=woocommerce_settings&tab=smart_offers') . '>' . __( 'Settings and Styles', SA_Smart_Offers::$text_domain ) . '</a>';
                    }
                    $views ['so-styles-settings'] = $so_settings_url;
                    if (!wp_script_is('thickbox')) {
                        if (!function_exists('add_thickbox')) {
                            require_once ABSPATH . 'wp-includes/general-template.php';
                        }
                        add_thickbox();
                    }
                    $views['smart_offers_support'] = '<a href="' . admin_url() . '#TB_inline?inlineId=smart_offers_post_query_form&post_type=smart_offers" class="thickbox">' . __( 'Support', SA_Smart_Offers::$text_domain ) . '</a>';
                    $views['smart_offers_docs'] = '<a href="http://www.storeapps.org/support/documentation/smart-offers/" title="' . __( 'Documentation', SA_Smart_Offers::$text_domain ) . '" target="_blank">' . __( 'Docs', SA_Smart_Offers::$text_domain ) . '</a>';
                    $views['smart_offers_demo'] = '<a href="http://demo.storeapps.org/?demo=so" title="' . __( 'Demo', SA_Smart_Offers::$text_domain ) . '" target="_blank">' . __( 'Demo', SA_Smart_Offers::$text_domain ) . '</a>';
                    $views['smart_offers_welcome'] = '<a href=' . admin_url('index.php?page=so-about') . '>' . __( 'About', SA_Smart_Offers::$text_domain ) . '</a>';                   
                }
            }

            return apply_filters( 'smart_offers_views', $views );
        }
        
        /**
	 * Check whether current theme is compatible fully with WC
	 */
        function so_check_hook_exists(){
            global $sa_smart_offers;
                            
            $template_compatibility_option = get_option( 'so_theme_compatibility' );
            
            if( empty( $template_compatibility_option ) ){

                $found_files = $add_shortcode_to_template = array();
                
                $files_path = array( 'Cart' => 'cart/cart.php', 
                                        'Checkout' => 'checkout/form-checkout.php', 
                                        'Order Received' => 'checkout/thankyou.php', 
                                        'My Account' => 'myaccount/my-account.php' );

                foreach ( $files_path as $key => $file ) {

                    if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
                            $found_files[$key] = '/' . $file ;
                    } elseif( file_exists( get_stylesheet_directory() . '/woocommerce/' . $file ) ) {
                            $found_files[$key] = '/woocommerce/' . $file ;
                    }
                }
                
                if( !empty( $found_files ) ){

                    foreach( $found_files as $page_nm => $file_path ){

                        $handle = fopen(get_stylesheet_directory() . $file_path, 'r');

                        $file_content = nl2br(htmlentities(file_get_contents( get_stylesheet_directory() . $file_path )));

                        if( $page_nm == 'Cart' ){
                            $search_string = ( $sa_smart_offers->is_wc_gte_20() )  ? 'woocommerce_before_cart' : 'woocommerce_before_cart_table' ;
                        } elseif( $page_nm == 'Checkout' ) {
                            $search_string = 'woocommerce_before_checkout_form';
                        } elseif( $page_nm == 'Order Received' ) {
                            $search_string = 'woocommerce_thankyou';
                        } elseif( $page_nm == 'My Account' ) {
                            $search_string = 'woocommerce_before_my_account';
                        } 
                        
                        $add_shortcode = false;
                        
                        preg_match('/\\b'.$search_string.'\\b/', $file_content, $str_matches);
                    
                        if( empty( $str_matches ) ){
                            $add_shortcode = true;
                        } else {
                            
                            preg_match_all('/(\/\*).*?(\*\/)|(\/\/).*?(\n)/s', $file_content, $comment_matches);
                            $prg = $comment_matches[0];
                            $prg_str = implode( ',', $prg);
                            
                            if( preg_match('/\\b'.$search_string.'\\b/', $prg_str, $str_comment_match )){
                                
                                if( ! empty( $str_comment_match ) ){
                                    $add_shortcode = true;
                                }
                            }
                        }
                        
                        if( $add_shortcode == true ) {
                            $add_shortcode_to_template[$page_nm]  = true;
                        }
                    }
                    
                    if( !empty( $add_shortcode_to_template ) ){
                        update_option( 'so_theme_compatibility', $add_shortcode_to_template);
                        update_option( 'so_theme_notice', "yes" );
                    }
                }
            }
        }
                        
        /**
	 * Show theme incompatibility message
	 */
        function so_show_theme_notice(){
                global $typenow, $pagenow, $post;

                $theme_compatibility = get_option( 'so_theme_compatibility' );

                if( ! empty( $theme_compatibility ) && get_option( 'so_theme_notice' ) == "yes" ){

                    if( 'smart_offers' == $typenow && ( 'post.php' == $pagenow || 'edit.php' == $pagenow ) ){

                        $pages = implode( ', ', array_keys( $theme_compatibility ) );
                        ?>
                        <div id="message" class="updated">
                            <div class="squeezer">
                                    <p><?php _e( '<strong> Your current theme is not compatible with Smart Offers. </strong>', SA_Smart_Offers::$text_domain ); ?></p>
                                    <p><?php _e( '<strong> We would need to add Smart Offers Shortcode to the following page/pages : ' . $pages . '. </strong>', SA_Smart_Offers::$text_domain ); ?></p>
                                    <p><a href="<?php echo esc_url( add_query_arg( 'so-theme-notice', 'add_shortcode' ) ) ;?>" class="wc-update-now button-primary"><?php _e( 'Fix this automatically', SA_Smart_Offers::$text_domain ); ?></a> <a href="<?php echo esc_url( add_query_arg( 'so-theme-notice', 'dismiss_theme_notice' ) ) ;?>" class="wc-update-now button-primary"><?php _e( 'Dismiss this notice', SA_Smart_Offers::$text_domain ); ?></a> <a href="http://www.storeapps.org/support/documentation/smart-offers/#so_shortcode" target="_blank" class="wc-update-now button-primary"><?php _e( 'Take to me the Documentation', SA_Smart_Offers::$text_domain ); ?></a> </p>
                            </div>
                        </div>
                        <?php

                    }                                 
                }


        }

        /**
	 * remove View button
	 */
        function so_remove_view_link_row($actions, $post) {

            if ($post->post_type != 'smart_offers')
                    return $actions;

            if (isset( $actions ['view'] )) {
                    unset( $actions ['view'] );
            }

            $actions['duplicate'] = '<a href="' . wp_nonce_url( admin_url( 'admin.php?action=duplicate_offer&amp;post=' . $post->ID ), 'woocommerce-duplicate-offer_' . $post->ID ) . '" title="' . __("Create a Duplicate Offer", SA_Smart_Offers::$text_domain)
            . '" rel="permalink">' .  __("Duplicate", SA_Smart_Offers::$text_domain) . '</a>';

            $actions['so_embed'] = '<a id ="so_embed_offer_'. $post->ID . '" href="#" title="' . __("Get embed code", SA_Smart_Offers::$text_domain) 
            . '" rel="permalink">' .  __("HTML", SA_Smart_Offers::$text_domain) . '</a>';

            return $actions;
        }

        /**
         * enqueue smart offers admin scripts
         * Javascript to generate HTML content of offer using ajax
         * 
         */
        function smart_offers_admin_scripts() {
            global $sa_smart_offers;

            if ( ( isset( $_GET['page'] ) && 'smart_offers' == $_GET['page'] ) || ( isset( $_GET['post_type'] ) && 'smart_offers' == $_GET['post_type'] ) ) {
                wp_enqueue_script( 'smart_offers_admin_js', trailingslashit( plugins_url() ) . 'smart-offers/assets/js/quick-bulk-edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
                wp_enqueue_style('so_frontend_css', trailingslashit( plugins_url() ) . 'smart-offers/assets/css/frontend.css');
                
                // ========================= Code to generate HTML content of offer ==========================

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

                
                $js = "jQuery('[id^=so_embed_offer_]').click(function(){
                            var offer_id = this.id.substr(15);

                            jQuery.ajax({
                                type: 'POST',
                                url: '" . admin_url('admin-ajax.php') . "',
                                dataType: 'json',
                                data: {
                                    action: 'generate_embed_offer',
                                    post_id: offer_id,
                                    security: '" . wp_create_nonce('so_generate_embed_offer') . "'
                                },
                                success: function( response ) {
                                    
                                    var content = response.content.replace('display:none','display:block');
                                    
                                    jQuery.magnificPopup.open({
                                        items: {
                                                src: '<div class =\"embed_offer\" ><h3 class=\"embed_head\" >" . __('Embed code for this offer: ', SA_Smart_Offers::$text_domain ) . "</h3><label style=\"padding-left:5px;\" ><small>" . __('copy following HTML code and paste it on a website or email as you like.', SA_Smart_Offers::$text_domain ) . "</small></label><hr style=\"padding:0px; margin:0px;\" ><textarea class =\"embed_text\" name =\"content\" id =\"so_offer_content\" >' + '<style>' + response.style + '</style>'+ content + '</textarea></div></div>',
                                                type: 'inline'
                                            },
                                        closeBtnInside: true,
                                        closeOnBgClick: true,
                                        showCloseBtn: true,
                                        tError: '". __('The content could not be loaded.' ,  SA_Smart_Offers::$text_domain ) . "'
                                    });
                                }
                            }); 
                        });";

                $sa_smart_offers->enqueue_js( $js );
            }
        }

        /**
         * function to display quick edit & bulk edit option for Smart Offers
         */
        function display_smart_offers_quick_bulk_edit_option( $column_name, $post_type ) {

            if ( empty( $post_type ) || $post_type != 'smart_offers' || $column_name != 'quick_stats' ) return;

            wp_nonce_field( 'smart_offers_quick_bulk_edit', 'smart_offers_quick_bulk_edit_nonce' );

            ?>
            <fieldset class="inline-edit-col-right inline-edit-<?php echo $post_type; ?>">
                <div class="inline-edit-col column-<?php echo $column_name; ?>">
                    <div class="inline-edit-group">
                        <label class="inline-edit-status alignleft" for="so_reset_quick_stats">
                            <span class="title"></span>
                            <input type="checkbox" name="so_reset_quick_stats" id="so_reset_quick_stats" value="yes" />
                            <?php echo __( 'Reset Quick Stats?', SA_Smart_Offers::$text_domain ); ?>
                        </label>
                    </div>
                </div>
            </fieldset>
            <?php

        }

        /**
         * function to handle quick edit action for smart offers
         */
        function process_quick_edit_smart_offers( $post_id = 0, $post = null ) {
            
            if (empty($post_id) || empty($post) || empty($_POST)) return;
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
            if (is_int(wp_is_post_revision($post))) return;
            if (is_int(wp_is_post_autosave($post))) return;
            if (!empty($_POST ['smart_offers_quick_bulk_edit_nonce']) && !wp_verify_nonce($_POST['smart_offers_quick_bulk_edit_nonce'], 'smart_offers_quick_bulk_edit')) return;
            if (!current_user_can('edit_post', $post_id)) return;
            if ($post->post_type != 'smart_offers') return;

            if ( isset( $_POST['so_reset_quick_stats'] ) && $_POST['so_reset_quick_stats'] == 'yes' ) {
                $this->reset_quick_stats( $post_id );
            }

        }

        /**
         * function to handle bulk edit action for smart offers
         */
        function process_bulk_edit_smart_offers() {

            $post_ids = ( ! empty( $_POST['post_ids'] ) ) ? $_POST['post_ids'] : array();
            $is_reset_quick_stats = ( ! empty( $_POST['so_reset_quick_stats'] ) && $_POST['so_reset_quick_stats'] == 'yes' ) ? true : false;

            if ( $is_reset_quick_stats ) {
                $this->reset_quick_stats( $post_ids );
            }            
            die();

        }

        /**
         * function to reset stats for given offer ids
         */
        function reset_quick_stats( $offer_ids = array(), $all = false ) {
            global $wpdb;

            if ( empty( $offer_ids ) && ! $all ) return;

            if ( ! is_array( $offer_ids ) ) {
                $offer_ids = array( $offer_ids );
            }

            $wpdb->query("SET SESSION group_concat_max_len=999999");

            $smart_offers_args = array(
                                        'post_type' => 'smart_offers',
                                        'fields' => 'ids',
                                        'nopaging' => true,
                                        'post_status' => 'any',
                                        'meta_query' => array(
                                                                'relation' => 'OR',
                                                                array(
                                                                        'key' => 'so_accept_skip_counter'
                                                                    ),
                                                                array(
                                                                        'key' => 'so_order_count'
                                                                    )
                                                            )
                                    );
            
            if ( ! $all ) {
                $smart_offers_args += array( 'post__in' => $offer_ids );
            }

            $smart_offers_results = new WP_Query( $smart_offers_args );

            $orders_args = array(
                                    'post_type' => 'shop_order',
                                    'fields' => 'ids',
                                    'nopaging' => true,
                                    'post_status' => 'any',
                                    'meta_query' => array(
                                                            array(
                                                                    'key' => 'smart_offers_meta_data'
                                                                )
                                                        )
                                );
            $orders_results = new WP_Query( $orders_args );

            if ( $smart_offers_results->post_count > 0 ) {

                $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta where meta_key IN ('so_accept_skip_counter', 'so_order_count') 
                                        AND post_id IN ( ".implode( ',', $smart_offers_results->posts )." )");

            }

            if ( $orders_results->post_count > 0 ) {

                $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta where meta_key = 'smart_offers_meta_data' 
                                        AND post_id IN ( ".implode( ',', $orders_results->posts )." )");

            }

        }                        

    }

    return new SO_Admin_Offers();
}