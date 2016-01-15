<?php
/**
 * Customer invoice email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>


<div style="margin-top:-80px !important">
<p><b>Invoice No: </b> <?php printf( $order->get_order_number() ); ?> / <?php printf( date_i18n( 'Ymd', strtotime( $order->order_date ) ) ); ?></p>
<p><b>Order No: </b><?php printf( $order->get_order_number() ); ?> - <?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?></p>
</div>

<?php if ( $order->has_status( 'pending' ) ) : ?>
<p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'woocommerce' ), get_bloginfo( 'name', 'display' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', 'woocommerce' ) . '</a>' ); ?></p>
<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?> 

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?> 

<div style="width:50%; float:left">

<h2 style="color:#00263c; font-size:18px">To</h1>

<?php do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text ); ?>  

</div>

<div style="width:50%; float:right; font-weight:normal">

<h2 style="color:#00263c; font-size:18px; font-weight:normal">From</h1> 

<p>Allergenics Limited<br />
PO Box 60156<br />
Titirangi<br />
Aucland 0642<br />
NEW ZEALAND<br />
GST Number: 118062544</p> 

</div>

<table class="td" cellspacing="0" cellpadding="6" style="color:#00263c; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	<thead>
		<tr>
			<th class="td" scope="col" style="text-align:left; color:#00263c;"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left; color:#00263c;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="td" scope="col" style="text-align:left; color:#00263c;"><?php _e( 'Price', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			switch ( $order->get_status() ) {
				case "completed" :
					echo $order->email_order_items_table( $order->is_download_permitted(), false, true );
				break;
				case "processing" :
					echo $order->email_order_items_table( $order->is_download_permitted(), true, true );
				break;
				default :
					echo $order->email_order_items_table( $order->is_download_permitted(), true, false );
				break;
			}
		?>
	</tbody>
	<tfoot>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?><tr>
						<td class="td" colspan="2" style="color:#00263c; text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px !important;'; ?>"><?php echo $total['label']; ?></td>
						<td class="td" style="color:#00263c; text-align:left; <?php if ( $i == 1 ) echo 'border-top-width: 4px !important;'; ?>"><?php echo $total['value']; ?></td>
					</tr><?php
				}
			}
		?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>
