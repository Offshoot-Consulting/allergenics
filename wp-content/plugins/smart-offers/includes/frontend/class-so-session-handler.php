<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('SO_Session_Handler')) {

    Class SO_Session_Handler {

        /**
	 * Set variable name and it's value in session
	 */
        static function so_set_session_variables($name, $value) {
            global $sa_smart_offers;

            if ( ! is_user_logged_in() && $name == 'sa_smart_offers_skipped_offer_ids' ) {

                $customer_id = self::so_get_customer_id();
                $option_name = 'sa_so_skipped_offer_ids_' . $customer_id;
                $current_value = get_option( $option_name, array() );
                $current_value[] = $value;
                $new_value = array_unique( $current_value );
                update_option( $option_name, $new_value );

            } else {

                $session_variables = array('sa_smart_offers_accepted_offer_ids', 'sa_smart_offers_skipped_offer_ids');

                if (isset($sa_smart_offers->global_wc()->session)) {

                    if (in_array($name, $session_variables)) {

                        $ids = (!isset($sa_smart_offers->global_wc()->session->$name) && !is_array($sa_smart_offers->global_wc()->session->$name) ) ? array() : $sa_smart_offers->global_wc()->session->$name;
                        $ids[] = $value;
                        $sa_smart_offers->global_wc()->session->$name = array_unique($ids, SORT_REGULAR);
                    } else {
                        $sa_smart_offers->global_wc()->session->$name = $value;
                    }

                } else {

                    if (in_array($name, $session_variables)) {

                        if (!isset($_SESSION [$name])) {
                            $_SESSION [$name] = array();
                        }

                        $_SESSION [$name][] = $value;
                    } else {

                        $_SESSION [$name] = $value;
                    }
                }
            }
        }

        /**
	 * Check whether a variable is set or not in session
	 */
        static function check_session_set_or_not($name) {
            global $sa_smart_offers;

            if ( ! is_user_logged_in() && $name == 'sa_smart_offers_skipped_offer_ids' ) {
                $customer_id = self::so_get_customer_id();
                $option_name = 'sa_so_skipped_offer_ids_' . $customer_id;
                $current_value = get_option( $option_name, array() );
                $bool = ( ! empty( $current_value ) ) ? true : false;
            } else {
                if (isset($sa_smart_offers->global_wc()->session)) {
                    $bool = ( isset($sa_smart_offers->global_wc()->session->$name) ) ? true : false;
                } else {
                    $bool = ( isset($_SESSION[$name]) ) ? true : false;
                }
            }

            return $bool;
        }

        /**
	 * Return a value of a variable set in session
	 */
        static function so_get_session_value($name) {
            global $sa_smart_offers;
            if ( ! is_user_logged_in() && $name == 'sa_smart_offers_skipped_offer_ids' ) {
                $customer_id = self::so_get_customer_id();
                $option_name = 'sa_so_skipped_offer_ids_' . $customer_id;
                $current_value = get_option( $option_name, array() );
                return $current_value;
            } else {
                if (isset($sa_smart_offers->global_wc()->session)) {
                    if (isset($sa_smart_offers->global_wc()->session->$name))
                        return $sa_smart_offers->global_wc()->session->$name;
                } else {
                    if (isset($_SESSION[$name]))
                        return $_SESSION[$name];
                }
            }
        }

        /**
	 * Delete the session variable
	 */
        static function so_delete_session($name) {
            global $sa_smart_offers;
            if ( ! is_user_logged_in() && $name == 'sa_smart_offers_skipped_offer_ids' ) {
                $customer_id = self::so_get_customer_id();
                $option_name = 'sa_so_skipped_offer_ids_' . $customer_id;
                delete_option( $option_name );
            } else {
                if (isset($sa_smart_offers->global_wc()->session)) {
                   unset($sa_smart_offers->global_wc()->session->$name);
                } else {
                    unset($_SESSION[$name]);
                }
            }
        }
        
        /**
	 * unset offer ids from accept/skip variable in session
	 */
        static function unset_offer_ids_from_session($offer_ids_to_unset) {
            global $sa_smart_offers;

            //Checking whehter session is set or not.
            $skipped_session_variable = self::check_session_set_or_not('sa_smart_offers_skipped_offer_ids');
            $accepted_session_variable = self::check_session_set_or_not('sa_smart_offers_accepted_offer_ids');

            // Getting skipped/accepted ids of session.
            $skipped_ids_in_session = ( $skipped_session_variable ) ? self::so_get_session_value('sa_smart_offers_skipped_offer_ids') : array();
            $accepted_ids_in_session = ( $accepted_session_variable ) ? self::so_get_session_value('sa_smart_offers_accepted_offer_ids') : array();

            if (!empty($offer_ids_to_unset)) {

                $offer_ids_to_unset = array_unique($offer_ids_to_unset);

                if (!empty($skipped_ids_in_session) || !empty($accepted_ids_in_session)) {

                    foreach ($offer_ids_to_unset as $offer_id) {

                        if (in_array($offer_id, $skipped_ids_in_session)) {
                            $key = array_search($offer_id, $sa_smart_offers->global_wc()->session->sa_smart_offers_skipped_offer_ids);
                            unset($skipped_ids_in_session[$key]);
                        }

                        if (in_array($offer_id, $accepted_ids_in_session)) {
                            $key = array_search($offer_id, $accepted_ids_in_session);
                            unset($accepted_ids_in_session[$key]);
                        }
                    }

                    if ( ! empty( $skipped_session_variable ) ) {
                        self::so_set_session_variables( 'sa_smart_offers_skipped_offer_ids', $skipped_ids_in_session );
                    }

                    if ( ! empty( $accepted_session_variable ) ) {
                        self::so_set_session_variables( 'sa_smart_offers_accepted_offer_ids', $accepted_ids_in_session );
                    }
                }
            }
        }

        /**
         * function to get unique id for user, create new if not already set
         */
        static function so_get_customer_id() {
            global $sa_smart_offers;
            
            $so_guest_id = ( ! empty( $_COOKIE['so_guest_id'] ) ) ? $_COOKIE['so_guest_id'] : null;

            if ( empty( $so_guest_id ) ) {
                $so_guest_id = $sa_smart_offers->global_wc()->session->get_customer_id();
                wc_setcookie( 'so_guest_id', $so_guest_id, ( time() + ( 60 * 60 * 48 )), apply_filters( 'wc_session_use_secure_cookie', false ) );
            }

            return $so_guest_id;

        }

    }
}