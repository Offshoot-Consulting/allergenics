<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
  
  <?php $items_in_cart = WC()->cart->cart_contents_count; ?>
  <?php $prod_ids_in_cart = array(); ?>
  
  <?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			array_push($prod_ids_in_cart, $product_id );
	} ?>
  
  <!-- check how many tests is in the cart -->
  <?php
  
  $test01 = 0;
  $test02 = 0;
  $test03 = 0;
  $test04 = 0;
  
    if(in_array("566", $prod_ids_in_cart)) {
      $test01 = 1;
    }
    if(in_array("568", $prod_ids_in_cart)) {
      $test02 = 1;
    }
    if(in_array("570", $prod_ids_in_cart)) {
      $test03 = 1;
    }
    if(in_array("572", $prod_ids_in_cart)) {
      $test04 = 1;
    } 
  
  $how_many_tests_in_cart = $test01 + $test02 + $test03 + $test04;
  echo 'there is ' . $how_many_tests_in_cart . ' tests in the cart';
  ?>
	
  <!-- if there is no urgent process in the cart -->		
	<?php if (!in_array("574", $prod_ids_in_cart)) { ?>
  
  <?php if($how_many_tests_in_cart > 0) { ?>
    <div class="urgent-box">
      <?php if($how_many_tests_in_cart == 1) { ?>
        <h3>Would you like to your test to be urgent???</h3>
        <p>Get your hair test processed urgently for only $20!</p>
        <a href="/cart/?add-to-cart=574&variation_id=719&attribute_amount=1">Yes! Make my test urgent</a>
      <?php } ?>
      
      <?php if($how_many_tests_in_cart > 1) { ?>
        <h3>Would you like to your tests to be urgent???</h3>
        <p>Get your hair tests processed urgently for only $20 per test!</p>
      <?php } ?>
      
      <?php if($how_many_tests_in_cart == 2) { ?>
        <a href="/cart/?add-to-cart=574&variation_id=720&attribute_amount=2">Yes! Make my tests urgent</a>
      <?php } ?>
      
      <?php if($how_many_tests_in_cart == 3) { ?>
        <a href="/cart/?add-to-cart=574&variation_id=721&attribute_amount=3">Yes! Make my tests urgent</a>
      <?php } ?>
      
      <?php if($how_many_tests_in_cart == 4) { ?>
        <a href="/cart/?add-to-cart=574&variation_id=722&attribute_amount=4">Yes! Make my tests urgent</a>
      <?php } ?> 
    </div>
    <?php } // end if($items_in_cart > 0) ?>
    
  <?php } // if (!in_array("574", $prod_ids_in_cart)) ?>

  <!-- if there is urgent process in the cart -->		
	<?php if (in_array("574", $prod_ids_in_cart)) { 
    	 
       if($how_many_tests_in_cart == 0) {
      	   mp_remove_product_from_cart();  
       }
       if($how_many_tests_in_cart == 1) {
           mp_remove_product_from_cart();
           WC()->cart->add_to_cart( '574' , '1' , '719' , '1' );
       }
       if($how_many_tests_in_cart == 2) {
           mp_remove_product_from_cart();
           WC()->cart->add_to_cart( '574' , '1' , '720' , '2' );
       }
       if($how_many_tests_in_cart == 3) {
           mp_remove_product_from_cart();
           WC()->cart->add_to_cart( '574' , '1' , '721' , '3' );
       }
       if($how_many_tests_in_cart == 4) {
           mp_remove_product_from_cart();
           WC()->cart->add_to_cart( '574' , '1' , '722' , '4' );
       }
   } ?>

<div class="cart_totals total_dv <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<!--<h2><?php _e( 'Cart Totals', 'woocommerce' ); ?></h2>-->

	<table cellspacing="0">

		<tr class="cart-subtotal">
			<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
			<td> <?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() ) : ?>

			<tr class="shipping">
				<th><?php _e( 'Shipping', 'woocommerce' ); ?></th>
				<td><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && WC()->cart->tax_display_cart == 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php _e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

<?php /*?>	<?php if ( WC()->cart->get_cart_tax() ) : ?>
		<p class="wc-cart-shipping-notice"><small><?php

			$estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' ' . __( ' (taxes estimated for %s)', 'woocommerce' ), WC()->countries->estimated_for_prefix() . __( WC()->countries->countries[ WC()->countries->get_base_country() ], 'woocommerce' ) )
				: '';

			printf( __( 'Note: Shipping and taxes are estimated%s and will be updated during checkout based on your billing and shipping information.', 'woocommerce' ), $estimated_text );

		?></small></p>
	<?php endif; ?><?php */?>

	

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
</div>
<div class="cart-collaterals subtotal_div">
<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">

						<!--<label for="coupon_code"><?php _e( 'Coupon', 'woocommerce' ); ?>:</label>--> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>
                
	<div align="right" class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<div class="wc-proceed-to-checkout">

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

	</div>

	
</div>

</div>
