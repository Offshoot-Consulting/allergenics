<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Admin_Settings')) {

    class SO_Admin_Settings {

        function __construct() {
            add_action('woocommerce_settings_tabs_smart_offers', array(&$this, 'sa_smart_offers_settings_tab_content'), 1);
            add_action('woocommerce_update_options_smart_offers', array(&$this, 'update_smart_offers_options'));
            add_filter('woocommerce_settings_tabs_array', array(&$this, 'sa_smart_offers_settings_tab'), 25);
            add_filter( 'plugin_action_links_' . plugin_basename( SO_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );
        }

        /**
	 * Show SO settings
	 */
        function sa_smart_offers_settings_tab_content() {

            $hidden_option = get_option('woo_sm_offer_show_hidden_items');
            $max_offer_to_show = get_option('so_max_inline_offer');

            $button_style_1 = get_option('smart_offers_button_style_1');
            $button_style_2 = get_option('smart_offers_button_style_2');
            $button_style_3 = get_option('smart_offers_button_style_3');
        ?>


            <style type="text/css">
                .accept_style_wrap {
                    margin-left: 2em;
                    margin-top: -1.5em;
                    font-size: 0.8em;
                    line-height: 1em;
                }
                :not(#custom_style).accept_style_wrap .accept_style_container .accept_button_holder a {
                    padding: 0.4em 1em 0.4em 1em !important;
                }
                .accept_style_wrap .accept_style_container .accept_button_holder a {
                    cursor: pointer;
                }
                #so_accept_button_styles_table,
                #custom_style_form_table {
                    margin-left: -0.7em;
                }
                #custom_style {
                    margin-top: -2.5em;
                    margin-bottom: -1.4em;
                }
                #custom_style a {
                    line-height: 1.2em !important;
                    width: initial !important;
                    height: initial !important;
                }
                #accept_style_1 {
                    font-size: 1em;
                    margin-top: -1.3em;
                }
            </style>
            <table class='form-table'>
                <tbody>
                    <tr>
                        <th class="titledesc"><?php _e('Preferences', SA_Smart_Offers::$text_domain); ?></th>
                        <td></td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><?php _e('Show Offers for hidden product', SA_Smart_Offers::$text_domain); ?></th>
                        <td class="forminp">
                            <select id="woo_sm_offer_show_hidden_items" name="woo_sm_offer_show_hidden_items">
                                <option value="yes" <?php selected('yes', $hidden_option); ?> ><?php _e('Yes', SA_Smart_Offers::$text_domain); ?></option>
                                <option value="no" <?php selected('no', $hidden_option); ?> ><?php _e('No', SA_Smart_Offers::$text_domain); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><?php _e('Multiple Offers? Select one with...', SA_Smart_Offers::$text_domain); ?></th>
                        <td class="forminp">
                            <fieldset>
                                <input type="radio" name="woo_sm_offers_if_multiple" id="high_price" <?php if (get_option('woo_sm_offers_if_multiple') == "high_price") echo 'checked="checked"'; ?> value="high_price" />
                                <label class="woo_sm_offers_if_multiple" id="high_price" for="high_price"><?php _e('Higher Price', SA_Smart_Offers::$text_domain); ?></label>
                            </fieldset>
                            <fieldset>
                                <input type="radio" name="woo_sm_offers_if_multiple" id="low_price" <?php if (get_option('woo_sm_offers_if_multiple') == "low_price") echo 'checked="checked"'; ?> value="low_price" />
                                <label class="woo_sm_offers_if_multiple" id="low_price" for="low_price"><?php _e('Lower Price', SA_Smart_Offers::$text_domain); ?></label>
                            </fieldset>
                            <fieldset>
                                <input type="radio" name="woo_sm_offers_if_multiple" id="random" <?php if (get_option('woo_sm_offers_if_multiple') == "random") echo 'checked="checked"'; ?> value="random" />
                                <label class="woo_sm_offers_if_multiple" id="random" for="random"><?php _e('Pick one randomly', SA_Smart_Offers::$text_domain); ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><?php _e('Maximum inline offers on a page', SA_Smart_Offers::$text_domain); ?></th>
                        <td class="forminp">
                            <input type="number" step="any" min="1" class="short" name="so_max_inline_offer" id="so_max_inline_offer" value="<?php echo $max_offer_to_show; ?>"> 
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc"><?php _e('Styles', SA_Smart_Offers::$text_domain); ?></th>
                        <td></td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><?php _e('Styles for Accept Button', SA_Smart_Offers::$text_domain); ?></th>
                        <td class="forminp">
                            <table id="so_accept_button_styles_table" class='form-table'>
                                <tbody>
                                    <td class="forminp" width="100px">
                                        <input type="radio" name="so_accept_button_styles" id="smart_offers_button_style_1" width="100px"
                                        <?php 
                                            if (get_option('so_accept_button_styles') == 'smart_offers_button_style_1' ) {
                                                echo 'checked="checked"';
                                            }
                                        ?> 
                                        value='smart_offers_button_style_1' />
                                        <div class="accept_style_wrap" id="accept_style_1">
                                            <div class="accept_style_container">
                                                <div class="accept_button_holder">
                                                    <a style="<?php echo $button_style_1; ?>"><?php echo  __( 'Button Style 1', SA_Smart_Offers::$text_domain ); ?></a> 
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="forminp" width="100px">
                                        <input type="radio" name="so_accept_button_styles"  id="smart_offers_button_style_2" width="100px"
                                        <?php 
                                            if (get_option('so_accept_button_styles') == 'smart_offers_button_style_2' ) {
                                                echo 'checked="checked"';
                                            }
                                        ?>
                                        value='smart_offers_button_style_2' />
                                        <div class="accept_style_wrap" id="accept_style_2">
                                            <div class="accept_style_container">
                                                <div class="accept_button_holder">
                                                    <a style="<?php echo $button_style_2; ?>"><?php echo  __( 'Button Style 2', SA_Smart_Offers::$text_domain ); ?></a> 
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="forminp" width="100px">
                                        <input type="radio" name="so_accept_button_styles"  id="smart_offers_button_style_3" width="100px"
                                        <?php
                                            if (get_option('so_accept_button_styles') == 'smart_offers_button_style_3' ) {
                                                echo 'checked="checked"';
                                            }
                                        ?>
                                        value='smart_offers_button_style_3' />
                                        <div class="accept_style_wrap" id="accept_style_3">
                                            <div class="accept_style_container">
                                                <div class="accept_button_holder">
                                                    <a style="<?php echo $button_style_3; ?>"><?php echo  __( 'Button Style 3', SA_Smart_Offers::$text_domain ); ?></a> 
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="forminp" width="100px">
                                        <input type="radio" name="so_accept_button_styles" id="smart_offers_custom_style_button" width="100px"
                                        <?php
                                            if (get_option('so_accept_button_styles') == 'smart_offers_custom_style_button' ) {
                                                echo 'checked="checked"';
                                            }
                                        ?>
                                        value='smart_offers_custom_style_button' />
                                        <div class="accept_style_wrap" id="custom_style">
                                            <div class="accept_style_container">
                                                <div class="accept_button_holder">
                                                    <a style="<?php 
                                                                    $so_css_for_accept = get_option( 'so_css_for_accept' );
                                                                    if ( ! empty( $so_css_for_accept ) ) {
                                                                        echo trim( stripslashes( $so_css_for_accept ) );
                                                                    } 
                                                    ?>"><?php echo  __( 'Custom style', SA_Smart_Offers::$text_domain ); ?></a> 
                                                </div>
                                            </div>
                                        </div>
                                        <div id="custom_button" width="100px"></div>
                                    </td>
                                </tbody>
                            </table>
                            <table id="custom_style_form_table">
                                <tbody>
                                    <td>
                                        <div class="custom_style_form">
                                            <textarea name="so_css_for_accept" id="so_css_for_accept" rows="5" cols="90">
                                                <?php
                                                    $so_css_for_accept = get_option( 'so_css_for_accept' );
                                                    if ( ! empty( $so_css_for_accept ) ) {
                                                        echo trim( stripslashes( $so_css_for_accept ) );
                                                    }
                                                ?>
                                            </textarea>
                                        </div>
                                    </td>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row"><?php _e('Styles for Skip Link', SA_Smart_Offers::$text_domain); ?></th>
                        <td class="forminp">
                            <fieldset>
                                <textarea name="so_css_for_skip" id="so_css_for_skip" rows="5" cols="90"><?php $style_skip = get_option('so_css_for_skip'); echo $style_skip; ?></textarea>
                            </fieldset>
                        </td>
                    </tr>
                </tbody>
            </table>

            <script type="text/javascript">

                jQuery(function() {

                    var isShowCustomStyleTextArea = function( show ) {
                        if ( show ) {
                            jQuery('table#custom_style_form_table').slideDown();
                        } else {
                            jQuery('table#custom_style_form_table').slideUp();
                        }
                    };

                    jQuery(document).on('ready', function() {
                        var show = jQuery('#smart_offers_custom_style_button').is(':checked');
                        isShowCustomStyleTextArea( show );
                    });

                    jQuery("input[name$='so_accept_button_styles']").on('click', function() {
                        var radio_value = jQuery(this).val();
                        var show = ( radio_value == 'smart_offers_custom_style_button' );
                        isShowCustomStyleTextArea( show );
                    });

                    jQuery("#so_css_for_accept").on('keyup', function(){
                        var textarea_value = jQuery(this).val();
                        textarea_value = jQuery.trim( textarea_value );
                        if ( textarea_value == '' ) {
                            jQuery('#custom_style').css('margin', '-1.3em 0 0 2em');
                            jQuery('#custom_style').css('font-size', '1em');
                        } else {
                            jQuery('#custom_style').css('margin', '-2.5em 0 -1.4em 2em');
                            jQuery('#custom_style').css('font-size', '0.8em');
                        }
                        jQuery("#custom_style a").attr('style',textarea_value);
                    });

                    jQuery('.accept_style_wrap .accept_style_container .accept_button_holder a').on('click', function(){
                        var target_element = jQuery(this).closest('td').find('input[name="so_accept_button_styles"]');
                        var target_value = target_element.val();
                        var show = ( target_value == 'smart_offers_custom_style_button' );
                        target_element.attr('checked', 'checked');
                        isShowCustomStyleTextArea( show );
                    });

                });

            </script>

            <?php

        }

        /**
	 * Save SO setting options
	 */
        function update_smart_offers_options() {

            if (isset($_POST ['woo_sm_offer_show_hidden_items']) && $_POST ['woo_sm_offer_show_hidden_items'] == 'yes') {
                update_option('woo_sm_offer_show_hidden_items', 'yes');
            } else {
                update_option('woo_sm_offer_show_hidden_items', 'no');
            }

            if (isset($_POST ['woo_sm_offers_if_multiple'])) {
                update_option('woo_sm_offers_if_multiple', $_POST ['woo_sm_offers_if_multiple']);
            }

            if (isset($_POST ['so_max_inline_offer'])) {
                update_option('so_max_inline_offer', $_POST ['so_max_inline_offer']);
            }

            if (isset($_POST ['so_accept_button_styles'])) {
                update_option('so_accept_button_styles', $_POST ['so_accept_button_styles']);
            }

            if (isset($_POST ['so_css_for_accept'])) {
                update_option('so_css_for_accept', $_POST ['so_css_for_accept']);
            }

            if (isset($_POST ['so_css_for_skip'])) {
                update_option('so_css_for_skip', $_POST ['so_css_for_skip']);
            }
        }

        /**
     * Add Smart Offers tab in WC settings
     */
        function sa_smart_offers_settings_tab($tabs) {
            $tabs ['smart_offers'] = __('Smart Offers', SA_Smart_Offers::$text_domain);
            return $tabs;
        }

        public function plugin_action_links( $links ) {
            $action_links = array(
                'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=smart_offers' ) . '" title="' . esc_attr( __( 'View Smart Offers Settings', SA_Smart_Offers::$text_domain ) ) . '">' . __( 'Settings', SA_Smart_Offers::$text_domain ) . '</a>',
            );

            return array_merge( $action_links, $links );
        }
        
        function can_show_hidden_items() {
            return get_option('woo_sm_offer_show_hidden_items');
        }
        
        function get_price_settings() {
            return get_option('woo_sm_offers_if_multiple');
        }
        

    }

    new SO_Admin_Settings();
}