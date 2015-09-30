<?php 

if ( !class_exists('JT_Colorful_FAQ_Settings_API' ) ):

class JT_Colorful_FAQ_Settings_API {

    private $settings_api;

    function __construct() {

        $this->settings_api = new JT_Colorful_FAQ_Settings_API_Class;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_submenu_page('edit.php?post_type=faq', 'FAQ Admin','FAQ Settings', 'edit_posts', 'colorful_faq_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'colorful_faq_general',
                'title' => __( 'Settings', 'jeweltheme' )
            )
        );
        return $sections;
    }


    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        
        $settings_fields = array(

    /* Colorful FAQ General Settings */
    'colorful_faq_general' => array(
                array(
                    'name' => 'posts_per_page',
                    'label' => __( 'FAQ Posts Per Page', 'jeweltheme' ),
                    'desc' => __( 'Choose FAQ Posts per page (-1 for All Posts).', 'jeweltheme' ),
                    'type' => 'text',
                    'default' => '-1'
                    ),
                array(
                    'name' => 'faq-title-bg-color',
                    'label' => __( 'Title Background Color', 'jeweltheme' ),
                    'desc' => __( 'Select FAQ Default Background Color. Default: #2c3e50', 'jeweltheme' ),
                    'default' => '#2c3e50',
                    'type' => 'color'
                ),  

                array(
                    'name' => 'faq-title-text-color',
                    'label' => __( 'Title Text Color', 'jeweltheme' ),
                    'desc' => __( 'Select FAQ Default Title Text Color. Default: #ffffff', 'jeweltheme' ),
                    'default' => '#ffffff',
                    'type' => 'color'
                ),

                array(
                    'name' => 'faq-bg-color',
                    'label' => __( 'Content Background Color', 'jeweltheme' ),
                    'desc' => __( 'Select FAQ Default Content Background Color. Default: #ffffff', 'jeweltheme' ),
                    'default' => '#ffffff',
                    'type' => 'color'
                ),                               

                array(
                    'name' => 'faq-text-color',
                    'label' => __( 'Content Text Color', 'jeweltheme' ),
                    'desc' => __( 'Select FAQ Content Default Text Color. Default: #444', 'jeweltheme' ),
                    'default' => '#444',
                    'type' => 'color'
                )
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif; 

$settings = new JT_Colorful_FAQ_Settings_API();
