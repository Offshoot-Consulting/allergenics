<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

<section class="order-top">
  <img src="<?php bloginfo('template_url');?>/images/success-top.jpg" alt="order successful" />
</section>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>
	
	<section class="order-one clearfix">
	 <div class="order-header">
	   <p class="order-main-text">Thank you for ordering with Allergenics.</p>
	 </div>
   <div class="order-left"> 
     <h3>What's next?</h3>   
	   <div class="whats-next">
        <div class="whats-next-step step01">
          <p>We need you to fill in our customer information form.</p>
          <a target="_blank" href="https://allergenics.typeform.com/to/AMcjtq?wcuserid=<?php echo $order->billing_email; ?>&orderid=<?php echo $order->get_order_number(); ?>">Click here</a>
        </div>   
        <div class="whats-next-step step02">
          <p>Send us your hair sample</p>
          <a target="_blank" href="/faqs">more info</a>
        </div>
      </div>
	 </div>
	 <div class="order-right">
	   <!--<h3>What to expect...</h3>-->
        <p>Please ensure you complete our customer information form which will give us a better picture of your state of health.</p>
        <p>We can not process your test until the form and hair sample have been received so please complete these steps as soon as you can. We will email you when your hair sample has been received and when your test results are ready.</p>
		<p style="margin-bottom:0">Please dont hesitate to contact us if you have any questions.</p>
	 </div>
	</section>
	
	<div class="clear"></div>
	
	<section class="order-two clearfix">
	 <div class="order-header">
	   <h3>Order Details</h3>
	 </div>
	 <div class="order-left"> 
	   <ul class="order_details">
			<li class="order">
				<img src="<?php bloginfo('template_url'); ?>/images/icon1.png" /><br />
        <?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<img src="<?php bloginfo('template_url'); ?>/images/icon2.png" /><br />
        <?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<img src="<?php bloginfo('template_url'); ?>/images/icon3.png" /><br />
        <?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<img src="<?php bloginfo('template_url'); ?>/images/icon4.png" /><br />
        <?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
	 </div>
	 <div class="order-right"> 
  	 <div class="edytas-tables"> 
      <?php //do_action( 'woocommerce_order_details_table',  ); ?>
      <?php woocommerce_order_details_table($order->id); ?>
    </div>
	 </div>
	</section>

	<div class="clear"></div>	
	
	<section class="order-three clearfix">
	 <div class="order-header">
	   <h3>Customer Details</h3>
	   <div class="order-left"> 
       <div class="edytas-tables"> 
        <?php //do_action( 'woocommerce_order_details_table',  ); ?>
        <?php woocommerce_order_details_table($order->id); ?>
      </div>
    </div>
	 </div>
	</section>

	<?php endif; ?>
	<?php //do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
  
<?php else : ?>
	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
<?php endif; ?>

</div>
