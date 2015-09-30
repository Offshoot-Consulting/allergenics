<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Footer')) {

    Class SO_Admin_Footer {

        function __construct() {
            add_action( 'in_admin_footer', array( &$this, 'add_social_links' ) );
        }
        /**
	 * Show Social buttons
	 */
        function add_social_links() {
            global $pagenow;
            if ( ! method_exists( 'Store_Apps_Upgrade', 'add_social_links' ) ) return;

            if ( ( ! empty( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] == 'smart_offers' && $pagenow == "edit.php" ) || ( ! empty( $_REQUEST['tab'] ) && $_REQUEST['tab'] == 'smart_offers' ) ) {
                echo '<div class="smart_offers_social_links" style="padding-bottom: 1em;">' . Store_Apps_Upgrade::add_social_links( 'smart_offers' ) . '</div>';
            }


        }
    }
    
    return new SO_Admin_Footer();

}
