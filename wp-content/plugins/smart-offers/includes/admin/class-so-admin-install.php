<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Install')) {

    /**
     * SO_Install Class
     */
    class SO_Admin_Install {

        /**
         * Hook in tabs.
         */
        public function __construct() {
            $this->install();
        }

        /**
         * Install SO
         */
        public function install() {

            // Redirect to welcome screen
            if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
                set_transient( '_so_activation_redirect', 1, 30 );            
            }

            $this->create_options();

            SO_Admin_Post_Type::register_post_type();
            
            // Flush rules after install
            flush_rewrite_rules();
        }

        /**
         * Default options
         *
         * Sets up the default options used on the settings page
         *
         * @access public
         */
        function create_options() {

            add_option('woo_sm_offer_show_hidden_items', 'yes');
            add_option('woo_sm_offers_if_multiple', 'high_price');
            add_option('so_max_inline_offer', 1);

            $so_css_for_accept = "display: block;
                                border-style: groove;
                                border-color: #ffab23;
                                border-width: 3px 4px 4px 3px;
                                height: 50px;
                                width: 320px;
                                background: #ffec64;
                                color: #333;
                                line-height: 2;
                                text-align: center;
                                font-size: 25px;
                                margin: auto;
                                text-decoration: none;
                                font-family: Myriad Pro, Impact, Helvetica, sans-serif;
                                font-weight: 800;
                                text-shadow: 1px 1px 0px #ffee66;
                                border-radius: 9px;";

            $so_css_for_skip =  "text-align: center; margin: auto;";
            
            add_option('so_css_for_accept', $so_css_for_accept);
            add_option('so_css_for_skip', $so_css_for_skip);

            // Persuade.css
            $button_style_1 = 'background:hsl(0,0%,26%);        
                                color:hsl(0,100%,100%);
                                text-decoration:none;
                                font-weight:400;
                                width:55%;
                                border:none;
                                -moz-border-radius:.6em;
                                -webkit-border-radius:.6em;
                                border-radius:.6em;
                                border-bottom:.3em solid hsl(0,0%,20%);
                                -moz-box-shadow:0 .3em 1.5em rgba(0,0,0,0.6)!important;
                                -webkit-box-shadow:0 .3em 1.5em rgba(0,0,0,0.6)!important;
                                box-shadow:0 .3em 1.5em rgba(0,0,0,0.6)!important;
                                text-align:center;
                                margin:.2em auto .5em auto;
                                padding:0.4em;
                                cursor: pointer;';

            // Eternal.css
            $button_style_2 = 'background: #e74c3c;
                                color: hsl(0, 33%, 98%);
                                font-weight: 700;
                                text-decoration: none; 
                                font-size: 1.25em;
                                width: 50%;
                                text-align: center;
                                -moz-box-sizing: content-box;
                                box-sizing: content-box;
                                margin:.5em auto 1.2em auto;
                                vertical-align: top;
                                padding: 0.8em 0.1em;
                                text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
                                border: 0;
                                border-bottom: 4px solid #BE3427;
                                cursor: pointer;';

            // Peak.css
            $button_style_3 = 'background: #936b0c;
                                color: hsl(0, 100%, 100%);
                                font-size: 1.3em;
                                vertical-align: top;
                                font-weight: 700;
                                text-align: center;
                                border-bottom: 3px solid rgba(0, 0, 0, 0.45);
                                -moz-border-radius: 3px;
                                -webkit-border-radius: 3px;
                                border-radius: 3px;
                                text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.5);
                                margin:.5em auto 1.5em auto;
                                width: 40%;
                                padding: 0.3em 1em;
                                cursor: pointer;';

            add_option('smart_offers_button_style_1', $button_style_1);
            add_option('smart_offers_button_style_2', $button_style_2);
            add_option('smart_offers_button_style_3', $button_style_3);

            $so_accept_button_styles = get_option( 'so_accept_button_styles' );

            if ( $so_accept_button_styles === false ) {

                $so_css_for_accept = get_option('so_css_for_accept');

                if ( ! empty( $so_css_for_accept ) ) {
                    add_option('so_accept_button_styles','smart_offers_custom_style_button');
                } else {
                    add_option('so_accept_button_styles','smart_offers_button_style_1');
                }

            }

            add_option('smart_offers_sample_data_imported','no');
        }

    }

}

return new SO_Admin_Install();
