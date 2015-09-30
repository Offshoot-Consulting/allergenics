<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

global $post, $sa_smart_offers;
$param_name = ( $sa_smart_offers->is_wc_gte_21() ) ? ( ( $sa_smart_offers->is_wc_gte_23() ) ? 'wc_enhanced_select_params' : 'woocommerce_admin_meta_boxes' ) : 'woocommerce_writepanel_params';
?>

<style type="text/css">
    div.woo_offer_rule {
        overflow: visible;
        opacity: 1
    }

    div.woo_offer_rule p.type select.action {
        margin-right: 7px;
        width: 165px
    }

    div.woo_offer_rule p.type button.remove_rule_option {
        float: right;
    }

    div.woo_offer_rule p.type select.role {
        margin-right: 7px
    }
</style>

<script type="text/javascript">

    jQuery(function() {

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

            var bindProductOnlyVariationsSelect2 = function() {

                jQuery( ':input.so-product-and-only-variations-search' ).filter( ':not(.enhanced)' ).each( function() {
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
                                    action:   jQuery( this ).data( 'action' ) || 'woocommerce_json_search_products_and_only_variations',
                                    security: '<?php echo wp_create_nonce("search-products-and-only-variations"); ?>'
                                };
                            },
                            results: function( data, page ) {
                                var terms = [];
                                if ( data ) {
                                    terms.push( { id: 'all', text: '<?php echo __( "All Products", SA_Smart_Offers::$text_domain ); ?>' } );
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

            bindProductOnlyVariationsSelect2();

            var bindCategorySelect2 = function() {

                jQuery( ':input.so-product-category-search' ).filter( ':not(.enhanced)' ).each( function() {
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
                                    action:   jQuery( this ).data( 'action' ) || 'woocommerce_json_search_prod_category',
                                    security: '<?php echo wp_create_nonce("so-search-product-category"); ?>'
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

            bindCategorySelect2();

        <?php } else { ?>

            var bindProductOnlyVariationsAjaxChosen = function() {
                jQuery("select.ajax_chosen_select_products_and_only_variations").ajaxChosen({
                    method: 'GET',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType: 'json',
                    afterTypeDelay: 100,
                    data: {
                        action: 'woocommerce_json_search_products_and_only_variations',
                        security: '<?php echo wp_create_nonce("search-products-and-only-variations"); ?>'
                    }
                }, function(data) {

                    var terms = { all: "<?php echo __( 'All Products', SA_Smart_Offers::$text_domain ); ?>" };

                    jQuery.each(data, function(i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });
            };

            bindProductOnlyVariationsAjaxChosen();

            var bindCategoryAjaxChosen = function() {
                jQuery("select.ajax_chosen_select_a_category").ajaxChosen({
                    method: 'GET',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType: 'json',
                    afterTypeDelay: 100,
                    data: {
                        action: 'woocommerce_json_search_prod_category',
                        security: '<?php echo wp_create_nonce("so-search-product-category"); ?>'
                    }
                }, function(data) {

                    var terms = {};

                    jQuery.each(data, function(i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });
            };

            bindCategoryAjaxChosen();

        <?php } ?>

        var loop;
        var last_index = jQuery('.woo_offer_rules .woo_offer_rule').last().index();

        jQuery('#offer_rules').on('click', 'a.add_new_rule', function() {

            if (loop == undefined) {

                var size_of_rules = jQuery('.woo_offer_rules .woo_offer_rule').length;

                if (size_of_rules == 0) {
                    loop = 0;
                } else {
                    loop = last_index + 1;
                }

            } else {
                loop = loop + 1;
            }

            var productSearchHtml = productCategorySearchHtml = '';

            <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                productSearchHtml = '<input type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;" name="search_product_ids_' + loop + '" data-placeholder="<?php _e( 'Search for a product&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="" value="" />';
                productCategorySearchHtml = '<input type="hidden" class="so-product-category-search" data-multiple="false" style="width: 50%;" name="search_category_ids_' + loop + '" data-placeholder="<?php _e( 'Search for a category&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_prod_category" data-selected="" value="" />';
            <?php } else { ?>
                productSearchHtml = '<select id="search_product_ids_' + loop + '" name="search_product_ids_' + loop + '[]" class="ajax_chosen_select_products_and_variations" multiple="multiple" data-placeholder="Search for a product"></select>';
                productCategorySearchHtml = '<select id="search_category_ids_' + loop + '" name="search_category_ids_' + loop + '[]" class="ajax_chosen_select_a_category" multiple="multiple" data-placeholder="Search for a category"></select>';
            <?php } ?>

            var html = '<div class="woo_offer_rule" >\
                            <p class="type">\
                                <label class="hidden"><?php _e('Type:', SA_Smart_Offers::$text_domain); ?></label>\
                                <select class="role" id="role" name="offer_type[' + loop + ']">\
                                        <option value="cartorder"><?php _e('Cart/Order', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="user"><?php _e('User', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="offer_valid_between"><?php _e('Offer Valid Between', SA_Smart_Offers::$text_domain); ?></option>\
                                </select>\
                                <label class="hidden"><?php _e('Action:', SA_Smart_Offers::$text_domain); ?></label>\
                                <select class="action" id="action" name="offer_action[' + loop + ']">\
                                        <option value="cart_contains" name="cartorder"><?php _e('Contains Product', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_doesnot_contains" name="cartorder"><?php _e('Does not contains Product', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_total_less" name="cartorder"><?php _e('Total is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_total_more" name="cartorder"><?php _e('Total is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_grand_total_less" name="cartorder"><?php _e('Grand Total is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_grand_total_more" name="cartorder"><?php _e('Grand Total is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_prod_categories_is" name="cartorder"><?php _e('Contains Products from Category', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="cart_prod_categories_not_is" name="cartorder"><?php _e('Does not contains Product from Category', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="has_bought" name="user"><?php _e('Has Purchased', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="not_bought" name="user"><?php _e('Has not Purchased', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="registered_user" name="user"><?php _e('Is', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="user_role" name="user"><?php _e('Is a', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="user_role_not" name="user"><?php _e('Is not a', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="registered_period" name="user"><?php _e('Is Registered for', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="total_ordered_less" name="user"><?php _e('Has previously Purchased less than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="total_ordered_more" name="user"><?php _e('Has previously Purchased more than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                </select>\
                                <input class="price" type="number" step="any" size="5" name="price[' + loop + ']" data-placeholder="Enter price" />\
                                <span id="search_product_ids_' + loop + '">\
                                    '+ productSearchHtml +'\
                                </span>\
                                <span id="search_category_ids_' + loop + '">\
                                    '+ productCategorySearchHtml +'\
                                </span>\
                                <label class="hidden"><?php _e('registered user action:', SA_Smart_Offers::$text_domain); ?></label>\
                                <select class="registered_user_action_' + loop + '" id="registered_user_action_' + loop + '" name="registered_user_action_' + loop + '">\
                                        <option value="yes"><?php _e('Registered', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="no"><?php _e('A Visitor', SA_Smart_Offers::$text_domain); ?></option>\
                                </select>\
                                <label class="hidden"><?php _e('registered period action:', SA_Smart_Offers::$text_domain); ?></label>\
                                <select class="registered_period_action_' + loop + '" id="registered_period_action_' + loop + '" name="registered_period_action_' + loop + '">\
                                        <option value="one_month" name="registered_period_one_month" ><?php _e('Less than 1 Month', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="three_month" name="registered_period_three_month"><?php _e('Less than 3 Months', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="six_month" name="registered_period_six_month"><?php _e('Less than 6 Months', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="less_than_1_year" name="registered_period_less_than_1_yr"><?php _e('Less than 1 Year', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="more_than_1_year" name="registered_period_more_than_1_yr"><?php _e('More than 1 Year', SA_Smart_Offers::$text_domain); ?></option>\
                                </select>\
                                <select class="user_role_' + loop + '" id="user_role_' + loop + '" name="user_role_' + loop + '">\
                                    <?php
                                        if (!isset($wp_roles)) {
                                            $wp_roles = new WP_Roles();
                                        }
                                        $all_roles = $wp_roles->roles;

                                        foreach ($all_roles as $role_id => $role) {
                                            echo '<option value="' . $role_id . '" name="' . $role_id . '" >' . $role['name'] . '</option>';
                                        }
                                    ?>\
                                </select>\
                                <span class="offer_dates_fields" name="offer_valid_between_' + loop + '" id="offer_valid_between_' + loop + '" ><label class="hidden"><?php _e('offer_valid_between:', SA_Smart_Offers::$text_domain); ?></label>\
                                <input type="text" class="short date-picker" name="_offer_valid_from_' + loop + '" id="_offer_valid_from_' + loop + '" placeholder="<?php _e('From&hellip; YYYY-MM-DD', 'placeholder', SA_Smart_Offers::$text_domain); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  />\
                                <input type="text" class="short date-picker" name="_offer_valid_till_' + loop + '" id="_offer_valid_till_' + loop + '" value="" placeholder="<?php _e('To&hellip; YYYY-MM-DD', 'placeholder', SA_Smart_Offers::$text_domain); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  />\
                                </span>\
                                <button type="button" class="remove_rule_option button" id="' + loop + '" >x</button></p>\
                                <p class="category_total_' + loop + '">\
                                    <select id="category_total_' + loop + '" name="category_total_' + loop + '" style="margin-left: 147px;width: 165px;margin-right: 7px;">\
                                        <option value="category_total_more"><?php _e('Subtotal of products in that category is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="category_total_less"><?php _e('Subtotal of products in that category is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                    </select>\
                                    <input type="number" class="category_amount" id="category_amount_' + loop + '" step="any" size="5" name="category_amount_' + loop + '" placeholder="<?php echo __( 'Enter price', SA_Smart_Offers::$text_domain ) ?>" style="width: 25%;">\
                                </p>\
                                <p class="quantity_total_' + loop + '">\
                                    <select id="quantity_total_' + loop + '" name="quantity_total_' + loop + '" style="margin-left: 147px;width: 165px;margin-right: 7px;">\
                                        <option value="quantity_total_more"><?php _e('Quantity is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                        <option value="quantity_total_less"><?php _e('Quantity is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>\
                                    </select>\
                                    <input type="number" class="cart_quantity" id="cart_quantity_' + loop + '" step="any" size="5" name="cart_quantity_' + loop + '" placeholder="<?php echo __( 'Enter Quantity(Optional)', SA_Smart_Offers::$text_domain ) ?>" style="width: 25%;">\
                                </p>\
                        </div>';

            jQuery('.woo_offer_rules').append( html );

            <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>

                jQuery( ':input.wc-product-search' ).filter( ':not(.enhanced)' ).each( function() {
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
                                    action:   jQuery( this ).data( 'action' ) || 'woocommerce_json_search_products_and_variations',
                                    security: <?php echo $param_name . ".search_products_nonce"; ?>
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

                bindProductOnlyVariationsSelect2();
                bindCategorySelect2();

            <?php } else { ?>

                jQuery("select.ajax_chosen_select_products_and_variations").ajaxChosen({
                    method: 'GET',
                    url: <?php echo $param_name . ".ajax_url"; ?>,
                    dataType: 'json',
                    afterTypeDelay: 100,
                    data: {
                        action: 'woocommerce_json_search_products_and_variations',
                        security: <?php echo $param_name . ".search_products_nonce"; ?>
                    }
                }, function(data) {

                    var terms = {};

                    jQuery.each(data, function(i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });

                bindProductOnlyVariationsAjaxChosen();
                bindCategoryAjaxChosen();

            <?php } ?>

            jQuery(".date-picker").datepicker({
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                showButtonPanel: true,
                showOn: "button",
                buttonImage: <?php echo $param_name . ".calendar_image"; ?>,
                buttonImageOnly: true
            });

            jQuery('select.role[name="offer_type[' + loop + ']"]').trigger('change');

            return false; // to stay on that area of page
        });


        jQuery('#offer_rules').on('change', 'select.role', function() {

            // Hiding all element at first
            jQuery(this).closest('.woo_offer_rule').find('select[name*="offer_action"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('input[name*="price"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('select[name*="search_product_ids_"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('span[id*="search_product_ids_"]').css('display', 'none');
//                                                                                jQuery(this).closest('.woo_offer_rule').find('span[id*="search_product_ids_to_remove_"]').css( 'display' , 'none' );
            jQuery(this).closest('.woo_offer_rule').find('span[id*="search_category_ids_"]').css('display', 'none');

            jQuery(this).closest('.woo_offer_rule').find('select[name*="registered_period_action_"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('select[name*="registered_user_action_"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('select[name*="user_role_"]').css('display', 'none');
            jQuery(this).closest('.woo_offer_rule').find('span[name*="offer_valid_between_"]').css('display', 'none');

            jQuery(this).closest('.woo_offer_rule').find('p[class*="category_total_"]').css('display', 'none');

            var id = jQuery(this).val();
            var name = jQuery(this).attr('name');
            var loop = name.split("[")[1].split("]")[0];

            if (id == "offer_valid_between") {

                jQuery(this).closest('.woo_offer_rule').find('span[name="offer_valid_between_' + loop + '"]').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('input[name="_offer_valid_from_' + loop + '"]').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('input[name="_offer_valid_till_' + loop + '"]').css('display', 'inline');

            } else {

                if (jQuery(this).data('options') == undefined) {
                    /*Taking an array of all options-2 and kind of embedding it on the select1*/
                    jQuery(this).data('options', jQuery('#action[name="offer_action[' + loop + ']"] option').clone());

                }

                jQuery(this).closest('.woo_offer_rule').find('select[name="offer_action[' + loop + ']"]').css('display', 'inline');
                var options = jQuery(this).data('options').filter('[name=' + id + ']');
                jQuery('select[name="offer_action[' + loop + ']"]').html(options);

                jQuery('select.action[name="offer_action[' + loop + ']"]').trigger('change');

            }

        });

        jQuery('#offer_rules').on('change', 'select.action', function() {

            var name = jQuery(this).attr('name');
            var loop = name.split("[")[1].split("]")[0];
            var id = jQuery('select[name="offer_action[' + loop + ']"] option:selected').text();

            // Return if select action is hidden
            if (jQuery(this).closest('.woo_offer_rule select[name="offer_action[' + loop + ']"]').is(":visible") == false) {
                return false;
            }

            if (id == 'Contains Product') {
                jQuery('p.quantity_total_' + loop + '').css('display', 'block');
            } else {
                jQuery('p.quantity_total_' + loop + '').css('display', 'none');
            }

            if (id == 'Contains Products from Category') {
                jQuery('p.category_total_' + loop + '').css('display', 'block');
            } else {
                jQuery('p.category_total_' + loop + '').css('display', 'none');
            }

            if (id == 'Total is less than or equal to' || id == 'Total is more than or equal to' || id == 'Grand Total is less than or equal to' || id == 'Grand Total is more than or equal to' || id == 'Has previously Purchased less than or equal to' || id == 'Has previously Purchased more than or equal to') {

                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'none');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'none');

            } else if (id == 'Is') {
                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'none');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'none');

            } else if (id == 'Is Registered for') {
                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'none');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'none');

            } else if (id == 'Is a' || id == 'Is not a') {
                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'inline');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'none');

            } else if (id == 'Contains Products from Category' || id == 'Does not contains Product from Category') {
                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'none');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'inline');

                limit_category(loop);
            } else if (id == 'Contains Product' || id == 'Does not contains Product' || id == 'Has Purchased' || id == 'Has not Purchased') {
                jQuery(this).closest('.woo_offer_rule').find('input.price').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_' + loop + '').css('display', 'inline');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_user_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="registered_period_action_' + loop + '"]').css('display', 'none');
                jQuery(this).closest('.woo_offer_rule').find('select[name="user_role_' + loop + '"]').css('display', 'none');
//                                                                                    jQuery(this).closest('.woo_offer_rule').find('span#search_product_ids_to_remove_'+loop+'').css( 'display' , 'none' );
                jQuery(this).closest('.woo_offer_rule').find('span#search_category_ids_' + loop + '').css('display', 'none');

            }

            return false;

        });

        function limit_category(loop) {
            var id = jQuery('select[name="offer_action[' + loop + ']"] option:selected').text();

            setTimeout(function() {
                <?php if ($sa_smart_offers->is_wc_gte_21()) { ?>

                    <?php if ( ! $sa_smart_offers->is_wc_gte_23() ) { ?>
                        if (jQuery('div#search_category_ids_' + loop + '_chosen ul.chosen-choices li').length >= 2 && ( id == 'Contains Products from Category' || id == 'Does not contains Product from Category' ) ) {

                            jQuery('div#search_category_ids_' + loop + '_chosen ul.chosen-choices li.search-field').css('visibility', 'hidden');
                            jQuery('div#search_category_ids_' + loop + '_chosen div.chosen-drop').css('display', 'none');
                            jQuery('p.category_total_' + loop + ' ').css('display', 'block');

                        } else {

                            jQuery('div#search_category_ids_' + loop + '_chosen ul.chosen-choices li.search-field').css('visibility', 'visible');
                            jQuery('div#search_category_ids_' + loop + '_chosen div.chosen-drop').css('display', 'block');
                            jQuery('p.category_total_' + loop + '').css('display', 'none');

                        }
                    <?php } ?>
                <?php } else { ?>
                    if (jQuery('div#search_category_ids_' + loop + '_chzn ul.chzn-choices li').length >= 2 && ( id == 'Contains Products from Category' || id == 'Does not contains Product from Category' ) ) {

                        jQuery('div#search_category_ids_' + loop + '_chzn ul.chzn-choices li.search-field').css('visibility', 'hidden');
                        jQuery('div#search_category_ids_' + loop + '_chzn div.chzn-drop').css('display', 'none');
                        jQuery('p.category_total_' + loop + ' ').css('display', 'block');

                    } else {

                        jQuery('div#search_category_ids_' + loop + '_chzn ul.chzn-choices li.search-field').css('visibility', 'visible');
                        jQuery('div#search_category_ids_' + loop + '_chzn div.chzn-drop').css('display', 'block');
                        jQuery('p.category_total_' + loop + '').css('display', 'none');

                    }

                <?php } ?>
            }, 1);

        }

        jQuery('#offer_rules').on('change', 'select[id^="search_category_ids_"]', function() {

            var id = jQuery(this).attr('id');
            var loop = id.split("search_category_ids_")[1];
            limit_category(loop);
        });


        // to remove rule
        jQuery('button.remove_rule_option').live('click', function() {

            var rule_id = jQuery(this).attr('id');

            <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                if (jQuery("input[name='price[" + rule_id + "]']").val().trim().length == 0 && jQuery('span#search_product_ids_' + rule_id + ' .select2-container ul.select2-choices li.select2-search-choice').length == 0 && !(jQuery("select[name='offer_type[" + rule_id + "]']").val() == "user" && (jQuery("select[name='offer_action[" + rule_id + "]']").val() == "registered_user" || jQuery("select[name='offer_action[" + rule_id + "]']").val() == "registered_period"))) {
            <?php } else { ?>
                if (jQuery("input[name='price[" + rule_id + "]']").val().trim().length == 0 && jQuery('div#search_product_ids_' + rule_id + '_chosen ul.chosen-choices li.search-choice').length == 0 && !(jQuery("select[name='offer_type[" + rule_id + "]']").val() == "user" && (jQuery("select[name='offer_action[" + rule_id + "]']").val() == "registered_user" || jQuery("select[name='offer_action[" + rule_id + "]']").val() == "registered_period"))) {
            <?php } ?>
                    answer = true;
                } else {
                    answer = confirm('<?php _e("Are you sure you want delete this rule?", SA_Smart_Offers::$text_domain); ?>');
                }

                if (answer) {
                    jQuery(this).closest('div').remove();
                }

                return false;

        });

    });

</script>


<?php
wp_nonce_field('woocommerce_save_data', 'woocommerce_meta_nonce');
?>

<div id="offers_options" class="panel woocommerce_options_panel">
    <div id="offer_rules" class="panel">
        <div class="woo_offer_rules">
            <?php
            $offer_rules = get_post_meta($post->ID, '_offer_rules', true);

            $loop = 0;

            if (is_array($offer_rules) && sizeof($offer_rules) > 0) {

                foreach ($offer_rules as $key => $value) {
                    ?>
                    <div class="woo_offer_rule">
                        <p class="type">
                            <label class="hidden"><?php _e('Type:', SA_Smart_Offers::$text_domain); ?></label>
                            <select class="role" id="role" name="offer_type[<?php echo $loop; ?>]">
                                <option <?php selected('cartorder', $value ['offer_type']); ?> value="cartorder"><?php _e('Cart/Order', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('user', $value ['offer_type']); ?> value="user"><?php _e('User', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('offer_valid_between', $value ['offer_type']); ?> value="offer_valid_between"><?php _e('Offer Valid Between ', SA_Smart_Offers::$text_domain); ?></option>
                            </select> 
                            <label class="hidden"><?php _e('Action:', SA_Smart_Offers::$text_domain); ?></label>
                            <select class="action" id="action" name="offer_action[<?php echo $loop; ?>]">
                                <option <?php selected('cart_contains', $value ['offer_action']); ?> value="cart_contains" name="cartorder"><?php _e('Contains Product', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_doesnot_contains', $value ['offer_action']); ?> value="cart_doesnot_contains" name="cartorder"><?php _e('Does not contains Product', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_total_less', $value ['offer_action']); ?> value="cart_total_less" name="cartorder"><?php _e('Total is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_total_more', $value ['offer_action']); ?> value="cart_total_more" name="cartorder"><?php _e('Total is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_grand_total_less', $value ['offer_action']); ?> value="cart_grand_total_less" name="cartorder"><?php _e('Grand Total is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_grand_total_more', $value ['offer_action']); ?> value="cart_grand_total_more" name="cartorder"><?php _e('Grand Total is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_prod_categories_is', $value ['offer_action']); ?> value="cart_prod_categories_is" name="cartorder"><?php _e('Contains Products from Category', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('cart_prod_categories_not_is', $value ['offer_action']); ?> value="cart_prod_categories_not_is" name="cartorder"><?php _e('Does not contains Product from Category', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('has_bought', $value ['offer_action']); ?> value="has_bought" name="user"><?php _e('Has Purchased', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('not_bought', $value ['offer_action']); ?> value="not_bought" name="user"><?php _e('Has not Purchased', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('registered_user', $value ['offer_action']); ?> value="registered_user" name="user"><?php _e('Is', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('user_role', $value ['offer_action']); ?> value="user_role" name="user"><?php _e('Is a', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('user_role_not', $value ['offer_action']); ?> value="user_role_not" name="user"><?php _e('Is not a', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('registered_period', $value ['offer_action']); ?> value="registered_period" name="user"><?php _e('Is Registered for', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('total_ordered_less', $value ['offer_action']); ?> value="total_ordered_less" name="user"><?php _e('Has previously Purchased less than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php selected('total_ordered_more', $value ['offer_action']); ?> value="total_ordered_more" name="user"><?php _e('Has previously Purchased more than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                            </select> 
                            <input 
                                value="<?php
                                        if ($value ['offer_action'] == 'cart_total_less' || $value ['offer_action'] == 'cart_total_more' || $value ['offer_action'] == 'cart_grand_total_less' || $value ['offer_action'] == 'cart_grand_total_more' || $value ['offer_action'] == 'total_ordered_less' || $value ['offer_action'] == 'total_ordered_more') {
                                            echo $value ['offer_rule_value'];
                                        } else {
                                            echo "";
                                        }
                                        ?>"
                                class="price" type="number" step="any" size="5" name="price[<?php echo $loop; ?>]" data-placeholder="Enter price" /> 
                            <span id="<?php echo 'search_product_ids_' . $loop; ?>">
                                <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                                    <input type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;" name="search_product_ids_<?php echo $loop; ?>" data-placeholder="<?php _e( 'Search for a product&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="<?php

                                        $json_ids = array();
                                        if ($value ['offer_action'] == 'cart_contains' || $value ['offer_action'] == 'has_bought' || $value ['offer_action'] == 'not_bought' || $value ['offer_action'] == 'cart_doesnot_contains') {
                                            
                                            $offer_rule_product_ids = explode(',', $value ['offer_rule_value']);

                                            foreach ($offer_rule_product_ids as $offer_rule_product_id) {
                                                if ($offer_rule_product_id) {
                                                    $product = $sa_smart_offers->get_product( $offer_rule_product_id );
                                                    $title = html_entity_decode( $sa_smart_offers->wc_get_formatted_name( $product ) );
                                                    if ( empty( $title ) ) continue;
                                                    $sku = ( ! empty( $product->sku ) ) ? ' (SKU: ' . $product->sku . ')' : '';
                                                    $json_ids[ $offer_rule_product_id ] = $title . $sku;
                                                }
                                            }
                                            echo esc_attr( json_encode( $json_ids ) );
                                        }
                                        ?>
                                    " value="<?php echo implode( ',', array_keys( $json_ids ) ); ?>" />
                                <?php } else { ?>
                                    <select id="<?php echo 'search_product_ids_' . $loop; ?>" name="<?php echo 'search_product_ids_' . $loop . '[]'; ?>" class="ajax_chosen_select_products_and_variations" multiple="multiple" data-placeholder="Search for a product"> 
                                        <?php
                                            if ($value ['offer_action'] == 'cart_contains' || $value ['offer_action'] == 'has_bought' || $value ['offer_action'] == 'not_bought' || $value ['offer_action'] == 'cart_doesnot_contains') {
                                                $offer_rule_product_ids = explode(',', $value ['offer_rule_value']);

                                                foreach ($offer_rule_product_ids as $offer_rule_product_id) {

                                                    if ($offer_rule_product_id) {

                                                        $product = $sa_smart_offers->get_product($offer_rule_product_id);
                                                        $title = $sa_smart_offers->get_formatted_product_name($product);
                                                        $sku = get_post_meta($offer_rule_product_id, '_sku', true);

                                                        if (!$title)
                                                            continue;

                                                        if (isset($sku) && $sku)
                                                            $sku = ' (SKU: ' . $sku . ')';

                                                        echo '<option value="' . $offer_rule_product_id . '" selected="selected">' . $title . $sku . '</option>';
                                                    }
                                                }
                                            } else {
                                                echo '<option value="" ></option>';
                                            }
                                        ?>
                                    </select>
                                <?php } ?>
                            </span> 
                            <span id="<?php echo 'search_category_ids_' . $loop; ?>">
                                <?php if ( $sa_smart_offers->is_wc_gte_23() ) { ?>
                                    <input type="hidden" class="so-product-category-search" data-multiple="false" style="width: 50%;" name="search_category_ids_<?php echo $loop; ?>" data-placeholder="<?php _e( 'Search for a category&hellip;', SA_Smart_Offers::$text_domain ); ?>" data-action="woocommerce_json_search_prod_category" data-selected="<?php

                                        $json_categories = array();

                                        if ($value ['offer_action'] == 'cart_prod_categories_is' || $value ['offer_action'] == 'cart_prod_categories_not_is') {
                                            
                                            $offer_rule_product_category_ids = array_filter( array_map( 'absint', explode( ',', $value ['offer_rule_value'] ) ) );

                                            $offer_rule_product_category_id = ( is_array( $offer_rule_product_category_ids ) ) ? current( $offer_rule_product_category_ids ) : $offer_rule_product_category_ids;

                                            if ( ! empty( $offer_rule_product_category_id ) ) {

                                                $category = get_term( $offer_rule_product_category_id, 'product_cat' );

                                                echo esc_attr( wp_kses_post( $category->name ) );

                                            } else {

                                                echo '';

                                            }

                                        }

                                    ?>" value="<?php if (!empty($offer_rule_product_category_id)) echo $offer_rule_product_category_id; ?>" />
                                <?php } else { ?>
                                    <select id="<?php echo 'search_category_ids_' . $loop; ?>" name="<?php echo 'search_category_ids_' . $loop . '[]'; ?>" class="ajax_chosen_select_a_category" multiple="multiple" data-placeholder="<?php echo __( 'Search for a category', SA_Smart_Offers::$text_domain ); ?>" >
                                        <?php
                                            if ($value ['offer_action'] == 'cart_prod_categories_is' || $value ['offer_action'] == 'cart_prod_categories_not_is') {
                                                $offer_rule_product_category_ids = explode(',', $value ['offer_rule_value']);

                                                foreach ($offer_rule_product_category_ids as $offer_rule_product_category_id) {

                                                    if ( ! empty( $offer_rule_product_category_id ) ) {

                                                        $category = get_term($offer_rule_product_category_id, 'product_cat');

                                                        if (!$category)
                                                            continue;

                                                        echo '<option value="' . $offer_rule_product_category_id . '" selected="selected">' . $category->name . '</option>';
                                                    }
                                                }
                                            } else {
                                                echo '<option value="" ></option>';
                                            }
                                        ?>
                                    </select>
                                <?php } ?>
                            </span>
                            <label class="hidden"><?php _e('registered user action:', SA_Smart_Offers::$text_domain); ?></label>
                            <select class="<?php echo 'registered_user_action_' . $loop; ?>" id="<?php echo 'registered_user_action_' . $loop; ?>" name="<?php echo 'registered_user_action_' . $loop; ?>"> 
                                <option value="yes" <?php selected('yes', $value ['offer_rule_value']); ?>><?php _e('Registered', SA_Smart_Offers::$text_domain); ?></option>
                                <option value="no" <?php selected('no', $value ['offer_rule_value']); ?>><?php _e('A Visitor', SA_Smart_Offers::$text_domain); ?></option>
                            </select> 
                            <label class="hidden"><?php _e('registered period action:', SA_Smart_Offers::$text_domain); ?></label>
                            <select class="<?php echo 'registered_period_action_' . $loop; ?>" id="<?php echo 'registered_period_action_' . $loop; ?>" name="<?php echo 'registered_period_action_' . $loop; ?>">
                                <option <?php if ($value ['offer_rule_value'] == 'one_month') echo 'selected="selected"'; ?> value="one_month" name="registered_period_one_month"><?php _e('Less than 1 Month', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php if ($value ['offer_rule_value'] == 'three_month') echo 'selected="selected"'; ?> value="three_month" name="registered_period_three_month"><?php _e('Less than 3 Months', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php if ($value ['offer_rule_value'] == 'six_month') echo 'selected="selected"'; ?> value="six_month" name="registered_period_six_month"><?php _e('Less than 6 Months', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php if ($value ['offer_rule_value'] == 'less_than_1_year') echo 'selected="selected"'; ?> value="less_than_1_year" name="registered_period_less_than_1_yr"><?php _e('Less than 1 Year', SA_Smart_Offers::$text_domain); ?></option>
                                <option <?php if ($value ['offer_rule_value'] == 'more_than_1_year') echo 'selected="selected"'; ?> value="more_than_1_year" name="registered_period_more_than_1_yr"><?php _e('More than 1 year', SA_Smart_Offers::$text_domain); ?></option>
                            </select>
                            <select class="<?php echo 'user_role_' . $loop; ?>" id="<?php echo 'user_role_' . $loop; ?>" name="<?php echo 'user_role_' . $loop; ?>">
                                <?php
                                    if (!isset($wp_roles)) {
                                        $wp_roles = new WP_Roles();
                                    }
                                    $all_roles = $wp_roles->roles;

                                    foreach ($all_roles as $role_id => $role) {
                                        echo '<option value="' . $role_id . '" name="' . $role_id . '" ' . selected(esc_attr($value ['offer_rule_value']), esc_attr($role_id), false) . '>' . $role['name'] . '</option>';
                                    }
                                ?>
                            </select>
                            <span class="offer_dates_fields" name="<?php echo 'offer_valid_between_' . $loop; ?>" id="<?php echo 'offer_valid_between_' . $loop; ?>" >
                            <label class="hidden"><?php _e('offer_valid_between:', SA_Smart_Offers::$text_domain); ?></label>
                                <input type="text" class="short date-picker" name="<?php echo '_offer_valid_from_' . $loop; ?>" id="<?php echo '_offer_valid_from_' . $loop; ?>" 
                                       value="<?php
                                                    if (is_array($value ['offer_rule_value']) && isset($value ['offer_rule_value']['offer_valid_from'])) {
                                                        echo!empty($value ['offer_rule_value']['offer_valid_from']) ? date_i18n('Y-m-d', $value ['offer_rule_value']['offer_valid_from']) : '';
                                                    }
                                                ?>"
                                       placeholder="<?php _e('From&hellip; YYYY-MM-DD', 'placeholder', SA_Smart_Offers::$text_domain); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  />
                                <input type="text" class="short date-picker" name="<?php echo '_offer_valid_till_' . $loop; ?>" id="<?php echo '_offer_valid_till_' . $loop; ?>" 
                                       value="<?php
                                                   if (is_array($value ['offer_rule_value']) && isset($value ['offer_rule_value']['offer_valid_till'])) {
                                                       echo (!empty($value ['offer_rule_value']['offer_valid_till']) && $value ['offer_rule_value']['offer_valid_till'] != '') ? date_i18n('Y-m-d', $value ['offer_rule_value']['offer_valid_till']) : '';
                                                   }
                                               ?>" 
                                       placeholder="<?php _e('To&hellip; YYYY-MM-DD', 'placeholder', SA_Smart_Offers::$text_domain); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  />
                            </span>
                            <button type="button" class="remove_rule_option button" id="<?php echo $loop; ?>">x</button>
                        </p>
                        <?php if ($value ['offer_action'] == 'cart_prod_categories_is') { ?>
                            <p class="<?php echo 'category_total_' . $loop; ?>">
                                <select id="<?php echo 'category_total_' . $loop; ?>" name="<?php echo 'category_total_' . $loop; ?>" style="margin-left: 147px;width: 165px;margin-right: 7px;">
                                    <option value="category_total_more" <?php selected('category_total_more', $value ['category_total']); ?>><?php _e('Subtotal of products in that category is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                    <option value="category_total_less" <?php selected('category_total_less', $value ['category_total']); ?>><?php _e('Subtotal of products in that category is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                </select>
                                <input type="number" class="<?php echo 'category_amount'; ?>" id="<?php echo 'category_amount_' . $loop; ?>" value="<?php echo $value ['category_amount']; ?>" step="any" size="5" name="<?php echo 'category_amount_' . $loop; ?>" placeholder="<?php echo __( 'Enter price(Optional)', SA_Smart_Offers::$text_domain ); ?>" style="width: 25%;">
                            </p>
                        <?php } 

                        if ($value ['offer_action'] == 'cart_contains') { ?>
                            <p class="<?php echo 'quantity_total_' . $loop; ?>">
                                <select id="<?php echo 'quantity_total_' . $loop; ?>" name="<?php echo 'quantity_total_' . $loop; ?>" style="margin-left: 147px;width: 165px;margin-right: 7px;">
                                    <option value="quantity_total_more" <?php selected('quantity_total_more', $value ['quantity_total']); ?>><?php _e('Quantity is more than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                    <option value="quantity_total_less" <?php selected('quantity_total_less', $value ['quantity_total']); ?>><?php _e('Quantity is less than or equal to', SA_Smart_Offers::$text_domain); ?></option>
                                </select>
                                <input type="number" class="<?php echo 'cart_quantity'; ?>" id="<?php echo 'cart_quantity_' . $loop; ?>" value="<?php echo $value ['cart_quantity']; ?>" step="any" size="5" name="<?php echo 'cart_quantity_' . $loop; ?>" placeholder="<?php echo __( 'Enter Quantity(Optional)', SA_Smart_Offers::$text_domain ); ?>" style="width: 25%;">
                            </p>
                        <?php } ?>

                    </div>

                        <?php
                        $loop ++;
                    }
                }
                ?>
            <script type="text/javascript">

                jQuery(function() {
                    jQuery('select.role').trigger('change');
                });
            </script>
        </div>
        <p>
            <a href="#" class="add_new_rule button"><?php _e('+ Add New Rule', SA_Smart_Offers::$text_domain); ?></a>
        </p>
    </div>
</div>
