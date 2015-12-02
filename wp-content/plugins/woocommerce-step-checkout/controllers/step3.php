<?php
include_once('front_template.php');
$obj= new Frontpage();
$obj->checkLogin();
$obj->steps();
$obj->step3();
	global $wpdb, $product, $woocommerce;
	get_header();

	
?>
<?php $product_array = $obj->check_product_exist(); ?>
<?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage woocommerce-page woocommerce">
                  <div class="container clearfix">
                    <div class="cont-left steps_left">
                    <h1><?php //the_title(); ?>Order your test now</h1>
                    <div id="msform">
<!-- progressbar -->
	<ul id="progressbar">
		<li class="active">Personal Details</li>
		<li class="active">Health Assessment</li>
		<li class="active">Choose Test</li>
		<li>Pay</li>
		
	</ul></div>
 

<!-- Modal -->


                    	<?php
                    	 $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'hair-tests'
                   
                )
            ),
            'post_type' => 'product',
            'orderby' => 'title'
        );
    $prd_query = new WP_Query($args);
    ?>
 
                       <ul class="products">
                     
                        	<?php while ( $prd_query->have_posts() ) : $prd_query->the_post(); 
                        		$product = new WC_Product( get_the_ID() );
								$imgName = $obj->get_image(get_the_ID());
                        	?>
                        	<li class="wcs_products" id="<?php echo get_the_ID(); ?>">
                                <div class="left"><img src="<?php echo plugins_url('woocommerce-step-checkout/assets/images/'.$imgName); ?>"></div>
                   				<div class="right">
                                    <h3 id="head_<?php echo get_the_ID(); ?>"><?php the_title(); ?></h3>
                                    <a class="learn_more" href="#modal-description" onclick="setPrdDes('<?php echo get_the_ID(); ?>');">Learn more</a>
                                    
                                    <?php echo $product->get_price_html(); ?>
                                    <div class="add_btn">
                                        <?php if(in_array(get_the_ID(), $product_array)) { ?>
                                        <a href="javascript:void(0);" class="button remove_from_cart" id="remove_<?php echo get_the_ID(); ?>" onclick="removeItem('<?php echo get_the_ID(); ?>')">Remove</a>
                                        <?php } else { ?>
                                        <a href="javascript:void(0);" class="button add_to_cart" id="add_<?php echo get_the_ID(); ?>" onclick="addtem('<?php echo get_the_ID(); ?>');">ADD</a>
                                        <?php } ?>
                                    </div>

                                </div>
                                <p style="display:none;" id="prd_desc_<?php echo get_the_ID(); ?>"><?php echo strip_tags(substr(get_the_excerpt(), 0, -100)); ?></p>
                            </li>
                        	<?php endwhile; // end of the loop. 
                        	 wp_reset_query(); 
                        	 ?>
                            </ul>

                            <?php
                            $product_info = new WC_Product( 574 );

	//echo '<h2 class="cat_name">Urgent</h2>';
	?>
	<div class="urgent_box_one_page">
	<div class="urgent-box">
		<h3>Need your results faster? </h3>
		<p>Add urgent processing to your order for just $20 per test, and get your results in just 5-7 working days. <a class="tell-me-more" href="#modal-one">Tell me more</a></p>
        <?php if(!in_array(574, $product_array)) { ?>
        <a href="javascript:void(0);" class="add_to_cart" id="add_574" onclick="addtem('574');">Yes process my tests urgently</a>
        <?php } else { ?>
        <a href="javascript:void(0);" class="remove_from_cart" id="remove_574" onclick="removeItem('574');">Remove urgent processing</a>
        <?php } ?>


        <div class="modal" id="modal-one" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-header">
                    <h2>Processing times</h2>
                    <a href="#close" class="btn-close" aria-hidden="true">&#10006;</a> <!--CHANGED TO "#close"-->
                    </div>
                    <div class="modal-body">
                    <p>Our standard processing time is 10 days. You can select to have your order processed within 3-4 days days by selecting the urgent processing option.</p>
                    <p>Please note the processing time starts from the date we receive your hair sample, and not the date you place the order online. We will send you an email to let you know when we have received your sample.</p>
                    </div>
                    <div class="modal-footer">
                    <a href="#close" class="btn">OK!</a>  <!--CHANGED TO "#close"-->
                    </div>
                    </div>
                    </div>

    </div>
	</div>

	<?php
                    	 $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => 'other-services'
                   
                )
            ),
            'post_type' => 'product',
            'orderby' => 'title'
        );
    $prd_query = new WP_Query($args);
    ?>
	<ul class="products personel_consultant">
		<?php while ( $prd_query->have_posts() ) : $prd_query->the_post(); 
                        		$product = new WC_Product( get_the_ID() );
                        	?>
                            <li class="wcs_products">
                                <div class="left"><img src="<?php echo plugins_url('woocommerce-step-checkout/assets/images/consult_half.jpg'); ?>"></div>
                                <div class="right">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo substr(get_the_excerpt(), 0,100).'...'; ?></p>
                                    <?php echo $product->get_price_html(); ?>
                                    <div class="add_btn">
                                        <?php if(in_array(get_the_ID(), $product_array)) { ?>
                                        <a href="javascript:void(0);" class="button remove_from_cart" id="remove_<?php echo get_the_ID(); ?>" onclick="removeItem('<?php echo get_the_ID(); ?>')">Remove</a>
                                        <?php } else { ?>
                                        <a href="javascript:void(0);" class="button add_to_cart" id="add_<?php echo get_the_ID(); ?>" onclick="addtem('<?php echo get_the_ID(); ?>');">ADD</a>
                                        <?php } ?>
                                    </div>

                                </div>
                   
                            </li>


                        	 

                </li>
                <?php endwhile; // end of the loop. 
                        	 wp_reset_query(); 
                        	 ?>

		</ul>
       
                    <?php the_content(); ?>
                    
                    </div>
                    <div class="cont-right step_3">
                        <div class="order_summery_sidebar">
                    <h3 id="order_review_heading">Order summary</h3>
<table style="width:100%;" id="product_list_order_summary" class="product_list_order_summary">


</table>
</div>
<div class="step-pagination"><a href="<?php echo home_url('/step-2'); ?>" style="float:left;" class=""><< back</a><a href="javascript:void(0)" class="btn" onclick="alert('Your cart is empty.');" style="float:right; <?php if ( $woocommerce->cart->get_cart_contents_count() == 0 ) { ?> display:block; <?php } else { ?> display:none; <?php } ?>" id="next_blank">Go to payment</a> <a id="next_move" href="<?php echo home_url('/step-4'); ?>" class="btn" style="float:right;<?php if ( $woocommerce->cart->get_cart_contents_count() > 0 ) { ?>display:block; <?php } else { ?> display:none; <?php } ?> ">Go to payment</a></div> 
						<!--<div class="widget order_summery_sidebar"><h3>Order summary</h3>
						<div id="product_list_order_summary1">

						</div>
						</div>-->

                    </div>

<div class="popup-box">
                    <div class="modal" id="modal-description" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-header">
                    <h2>Description</h2>
                    <a href="#close" class="btn-close" aria-hidden="true">&#10006;</a> <!--CHANGED TO "#close"-->
                    </div>
                    <div class="modal-body">
                    
                    </div>
                   
                    </div>
                    </div>
                </div>
                  </div>
                    

                </section>
        <?php endwhile; ?>
<?php    
	get_footer();
?>