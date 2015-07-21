<?php
/*
  Plugin Name: Gravity Forms GEO Fields Add-on
  Plugin URI: http://www.geomywp.com
  Description: Add GEO location to posts from the front end using Gravity Forms
  Author: Eyal Fitoussi
  Version: 1.5
  Author URI: http://www.geomywp.com
  Text Domain: GGF
  Domain Path: /languages/
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Activate add-on when activating plugin
 * 
 * @since 1.4
 */
function ggf_activate_addon() {

    $gmw_addons = get_option( 'gmw_addons' );

    if ( !isset( $gmw_addons ) )
        $gmw_addons = array();

    $gmw_addons['gravity_forms_geo_fields'] = 'active';
    update_option( 'gmw_addons', $gmw_addons );

}
register_activation_hook( __FILE__, 'ggf_activate_addon' );

/**
 * Deactivate add-on when Deactivating plugin
 * 
 * @since 1.4
 */
function ggf_deactivate_addon() {

    $gmw_addons = get_option( 'gmw_addons' );

    if ( !isset( $gmw_addons ) )
        return;

    unset( $gmw_addons['gravity_forms_geo_fields'] );
    update_option( 'gmw_addons', $gmw_addons );

}
register_deactivation_hook( __FILE__, 'ggf_deactivate_addon' );

/**
 * Gravity_Forms_GEO_Fields class.
 */
class Gravity_Forms_GEO_Fields {

    /**
     * @var Gravity Forms Geo
     * @since 1.4
     */
    private static $instance;

    /**
     * Main Instance
     *
     * Insures that only one instance of Gravity_Forms_GEO_Fields exists in memory at any one
     * time.
     *
     * @since 1.4
     * @static
     * @staticvar array $instance
     * @return GEO_Job_Manager
     */
    public static function instance() {

        if ( !isset( self::$instance ) && !( self::$instance instanceof Gravity_Forms_GEO_Fields ) ) {

            self::$instance = new Gravity_Forms_GEO_Fields;
            self::$instance->constants();
            self::$instance->includes();
            self::$instance->actions();
            self::$instance->load_textdomain();

            if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
                self::$instance->check_updates();
            }
        }

        return self::$instance;

    }

    /**
     * __construct function.
     */
    public function __construct() {
        
    }

    /**
     * declare constants 
     * 
     * @since 1.4
     */
    private function constants() {

        //define globals
        define( 'GGF_LICENSE_NAME', 'gravity_forms_geo_fields' );
        define( 'GGF_ITEM_NAME', 'Gravity Forms Geo Fields' );
        define( 'GGF_VERSION', '1.5' );
        define( 'GGF_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
        define( 'GGF_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

        if ( !defined( 'GMW_REMOTE_SITE_URL' ) ) {
            define( 'GMW_REMOTE_SITE_URL', 'https://geomywp.com' );
        }

    }

    /**
     * 
     * Include file
     * 
     * @since 1.4
     */
    private function includes() {

        //functions file
        include_once GGF_PATH . '/includes/functions.php';

        //admin files
        if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
			//settings page
        	include( 'includes/admin/ggf-admin.php' );
                
        	//include plugin updater files

       		include_once GGF_PATH . '/updater/geo-my-wp-addons.php';

        	include_once GGF_PATH . '/updater/geo-my-wp-license-handler.php';
        }

      
    }

    /**
     * Include file
     * 
     * @since 1.4
     */
    private function actions() {

        add_filter( 'plugin_action_links', array( $this, 'addons_action_links' ), 10, 2 );
        add_filter( 'gmw_admin_addons_page', array( $this, 'addon_init' ) );
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_register_scripts' ) );

    }

    /**
     * add gmw action links in plugins page
     * @param $links
     * @param $file
     */
    public function addons_action_links( $links, $file ) {
        static $this_plugin;

        $addons   = get_option( 'gmw_addons' );
		$licenses = get_option( 'gmw_license_keys' );
		$statuses = get_option( 'gmw_premium_plugin_status' );
        
        if ( $file == 'gravity-forms-geo-fields/geo-fields.php' ) {

            if ( isset( $addons[GGF_LICENSE_NAME] ) && $addons[GGF_LICENSE_NAME] == 'active' && isset( $licenses[GGF_LICENSE_NAME] ) && !empty( $licenses[GGF_LICENSE_NAME] ) && isset( $statuses[GGF_LICENSE_NAME] ) && $statuses[GGF_LICENSE_NAME] == 'valid' ) {
                $links['deactivate'] = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=gmw-add-ons">' . __( 'Deactivate license before deactivating the plugin', 'GGF' ) . '</a>';
            } else {
                array_unshift( $links, '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=gmw-add-ons">' . __( 'Activate license key', 'GGF' ) . '</a>' );
            }
        }

        return $links;

    }

    /**
     * Include addon function.
     *
     * @access public
     * @return $addons
     */
    public function addon_init( $addons ) {

        $addons[9] = array(
            'name'    => GGF_LICENSE_NAME,
            'title'   => __( 'Gravity Forms GEO Fields', 'GGF' ),
            'version' => GGF_VERSION,
            'desc'    => __( 'Add GeoLocation features to Gravity Forms.', 'GGF' ),
            'image'   => GGF_URL . '/assets/images/addon-image.jpg',
            'require' => array(
                'Gravity Forms plugin' => array( 'plugin_file' => 'gravityforms/gravityforms.php', 'link' => 'http://gravityforms.com' )
            ),
            'license' => true
        );
        return $addons;

    }

    /**
     * Localisation
     *
     * @access public
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'GGF', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    /**
     * Check for plugin updates
     * 
     * @since 1.0
     */
    protected function check_updates() {

    	if ( class_exists( 'GMW_License' ) ) {
    		$ggf_license = new GMW_License( __FILE__, GGF_ITEM_NAME, GGF_LICENSE_NAME, GGF_VERSION, 'Eyal Fitoussi' );
    	}

    }

    /**
     * frontend_scripts function.
     *
     * @access public
     * @return void
     */
    public function frontend_register_scripts() {

        wp_register_script( 'ggf-js', GGF_URL . '/assets/js/gf.js', array(), false, true );
        wp_register_style( 'ggf-style', GGF_URL . '/assets/css/style.css' );
        wp_deregister_script( 'google-maps' );
        //if ( !wp_script_is( 'google-maps', 'registered' ) )
            wp_register_script( 'google-maps', '//maps.googleapis.com/maps/api/js?sensor=false&libraries=places', array(), false );

    }

}

/**
 *  GGF Instance
 *
 * @since 1.4
 * @return Gravity Forms GEO Fields Instance
 */
function GGF() {

    //make sure that WP Job Manager is activated
    if ( !class_exists( 'GFForms' ) ) {

        function ggf_deactivated_admin_notice() {
            ?>
            <div class="error">
                <p><?php _e( 'Gravity Forms GEO Fields requires Gravity Forms plugin in order to work. Please activate install/activate Gravity Forms plugin or deactivate Gravity Forms GEO Field.', 'GGF' ); ?></p>
            </div>  
            <?php

        }
        return add_action( 'admin_notices', 'ggf_deactivated_admin_notice' );
    }

    return Gravity_Forms_GEO_Fields::instance();

}
// Get GGF Running
add_action( 'plugins_loaded', 'GGF' );
