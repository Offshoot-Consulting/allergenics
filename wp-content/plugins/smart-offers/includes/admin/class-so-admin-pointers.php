<?php
/**
 * Adds and controls pointers for contextual help/tutorials.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SO_Admin_Pointers Class
 */
class SO_Admin_Pointers {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'sa_so_set_pointer_for_screen' ) );
	}

	/**
	 * Setup pointers for screen.
	 */
	public function sa_so_set_pointer_for_screen() {
		$screen = get_current_screen();

		switch ( $screen->id ) {
			case 'smart_offers' :
				$this->so_after_checkout_notice();
			break;
		}
	}

	/**
	 * Pointers for showing a notice on After Checkout page option
	 */
	public function so_after_checkout_notice() {

		$so_pointers = array(
			'pointers' => array(
				'offer_rule_post_checkout_page' => array(
										'target' => "#offer_rule_post_checkout_page + .description",
										'options' => array(
											'content' => '<h3>' . esc_html__( 'After Checkout & your Payment Gateways', SA_Smart_Offers::$text_domain ) . '</h3>' .
														'<p>' . esc_html__( 'This may not work with your payment gateway. It may not work if your site accepts on-site payments like with Paypal Pro or Stripe. It works with basic Paypal.', SA_Smart_Offers::$text_domain ) . '</p>',
											'position' => array(
												'edge' => 'left',
												'align' => 'left'
											)
										)
									)
			)
		);

		$this->enqueue_pointers( $so_pointers );
	}

	/**
	 * Enqueue pointers and add script to page.
	 * @param array $so_pointers
	 */
	public function enqueue_pointers( $so_pointers ) {

		global $sa_smart_offers;

		$so_pointers = json_encode( $so_pointers );

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		$js = "jQuery('body').on( 'click', 'input#offer_rule_post_checkout_page', function() {
					var so_pointers = {$so_pointers};

					setTimeout( init_so_pointers, 25 );
	
					function init_so_pointers() {
						jQuery.each( so_pointers.pointers, function( i ) {
							show_sa_so_pointer( i );
							return false;
						});
					}

					function show_sa_so_pointer( id ) {
						var pointer = so_pointers.pointers[ id ];
						var options = jQuery.extend( pointer.options, {
							close: function() {
								if ( pointer.next ) {
									show_sa_so_pointer( pointer.next );
								}
							}
						});
						var this_pointer = jQuery( pointer.target ).pointer( options );
						this_pointer.pointer( 'open' );

						if ( pointer.next_trigger ) {
							jQuery( pointer.next_trigger.target ).on( pointer.next_trigger.event, function() {
								setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
							});
						}
					}
			});
		";

		$sa_smart_offers->enqueue_js( $js );
	}


}

new SO_Admin_Pointers();