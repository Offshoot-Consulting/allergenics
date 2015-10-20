<?php
/**
 * Order Customer Details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header><h2><?php _e( 'Customer Details', 'woocommerce' ); ?></h2></header>

<table class="shop_table shop_table_responsive customer_details">
	<?php if ( $order->customer_note ) : ?>
		<tr>
			<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
			<td><?php echo wptexturize( $order->customer_note ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_email ) : ?>
		<tr>
			<th><?php _e( 'Email:', 'woocommerce' ); ?></th>
			<td><?php echo esc_html( $order->billing_email ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $order->billing_phone ) : ?>
		<tr>
			<th><?php _e( 'Telephone:', 'woocommerce' ); ?></th>
			<td><?php echo esc_html( $order->billing_phone ); ?></td>
		</tr>
	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>


<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>  

<?php endif; ?> 

<tr>
	<th><?php _e( 'Billing Address', 'woocommerce' ); ?></th>
	<td><?php echo ( $address = $order->get_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?></td>
</tr>


<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

<tr>
		<th><?php _e( 'Shipping Address', 'woocommerce' ); ?></th>
		<td><?php echo ( $address = $order->get_shipping_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?></td>
</tr>

<?php endif; ?>  

</table>
