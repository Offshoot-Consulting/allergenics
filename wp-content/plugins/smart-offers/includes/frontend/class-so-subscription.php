<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SO_Subscription' ) ) {

	class SO_Subscription {

		public function __construct() {

			add_filter( 'so_link_args', array( $this, 'add_subscription_args' ), 10, 3 );
			add_filter( 'valid_product_types_for_known_ids', array( $this, 'add_subscription_product_types' ) );
			add_filter( 'valid_product_types_for_unknown_ids', array( $this, 'add_subscription_product_types' ) );

		}

		public function add_subscription_args( $args, $offer_id, $action ) {

			if ( empty( $args ) || $action == 'skip' ) return $args;

			$so_offers = new SO_Offers();
			$user_details = $so_offers->get_user_details();
			$user_has_bought = ( ! empty( $user_details['offer_rule_has_bought'] ) ) ? explode( ',', $user_details['offer_rule_has_bought'] ) : array();

			$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions();

			$preserve_keys = true;
			$subscriptions = array_reverse( $subscriptions, $preserve_keys );

			foreach ( $subscriptions as $subscription_key => $subscription ) {
				if ( in_array( $subscription['product_id'], $user_has_bought ) && 'active' == $subscription['status'] ) {
					$args['switch-subscription'] = $subscription_key;
					$args['auto-switch'] = 'true';
					break;
				}
			}
			return $args;

		}

		public function add_subscription_product_types( $product_types = array() ) {

			$current_filter = current_filter();

			if ( $current_filter == 'valid_product_types_for_known_ids' ) {
				$product_types[] = 'subscription_variation';
			} elseif ( $current_filter == 'valid_product_types_for_unknown_ids' ) {
				$product_types[] = 'variable-subscription';
			}

			return $product_types;

		}

	}

}

return new SO_Subscription();