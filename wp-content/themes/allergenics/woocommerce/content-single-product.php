<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/slick.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/slick-theme.css"/>


	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">

.slick-prev:before, .slick-next:before { color:#000 !important; }
.testmonial-block { width:100%; padding:35px; }

.slideme center p {
    border-bottom: 1px dotted #ccc;
    margin-top: 25px;
	 color: #329c87 !important;
    font-size: 13px !important;
    font-style: normal !important;
}
.slideme center {
    border: 1px solid #ddd;
    padding: 80px 50px;
}
.slideme {
    padding: 10px;
}
</style>
<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
	
?>

<div class="container">
        <div id="content">
        
                
                <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->
    <?php if($post->ID != '576') { ?>
    <div class="single_prd_testimonial">
    <h2 class="what_customer">What our customers are saying</h2>
    </div>
    	<?php $args = array(
	'posts_per_page'   => 5,
	'offset'           => 0,
	'orderby'          => 'ID',
	'order'            => 'DESC',
	'post_type'        => 'testimonial',
	'post_status'      => 'publish',
	
);
$testimonials = get_posts( $args ); ?>
    <section id="features" class="blue">
<div class="testmonial-block">
<div class="slider multiple-items">
					<?php foreach ( $testimonials as $testimonial ) { ?>
						
						<div class="slideme"><center><?php echo $testimonial->post_content; ?><p><?php echo get_post_meta($testimonial->ID,'_ikcf_client',true); ?></p></center></div>
						
					<?php }
wp_reset_postdata();?>
					
                   
				</div>
</div>
			
		</section>

        <div class="single_prd_description">
    		<h2>What we test for</h2>
    	</div>
              
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
 <?php } ?> 
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div>
                
                <div style="clear:both"></div>
           
        </div>
     
        
                
        
        
    </div>



<?php do_action( 'woocommerce_after_single_product' ); ?>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/slick.js"></script>

		<script type="text/javascript">
		jQuery('.multiple-items').slick({
  infinite: true,
  slidesToShow: 2,
  slidesToScroll: 2,
  autoplay: true,
  autoplaySpeed: 2000
});
</script>