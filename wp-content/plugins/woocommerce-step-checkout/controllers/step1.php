<?php
global $wpdb, $session;
include_once('front_template.php');
$obj= new Frontpage();

$obj->check_step1();
$obj->steps();
 
	get_header();
	if(isset($_POST['login']) && $_POST['login'] == 'Login') {
		$form_name = $_POST['login'];
		$for_whome_text = $_POST['for_whome_text'];
	}
	else if(isset($_POST['register']) && $_POST['register'] == 'Register') { 
		$form_name = $_POST['register'];
		$for_whome_text = $_POST['for_whome_text'];
	}
	else {
		$form_name = '';
		$for_whome_text = '';
	}
	echo $_POST['for_whome_text'];
?>
<script type="text/javascript">
jQuery(window).load(function() {
show_form('<?php echo $form_name; ?>');
show_client('<?php echo $for_whome_text; ?>');
});
</script>
 <?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage">
                  <div class="container clearfix">
                    <div class="cont-left steps_left">
                    <h1><?php //the_title(); ?>Order your test now</h1>
                    <!-- progressbar -->
					<div id="msform">

						<ul id="progressbar">
						<li class="active">Personal Details</li>
						<li>Health Assessment</li>
						<li>Choose Test</li>
						<li>Pay</li>
					
						</ul>

					</div>
					<?php //echo '<pre>'; print_r($_POST); ?>
					<div class="selection_step1 woocommerce">
						<form>
						<p class="form-row form-row-wide first_p">
						<label for="for_whome">Who is this test for? <span class="required">*</span></label>
						</p>
						<p class="form-row form-row-wide second_p">
						<input type="radio" name="for_whome" id="myself" value="0" <?php if((isset($_POST['for_whome_text']) && $_POST['for_whome_text'] == 0) || !isset($_POST['for_whome_text'])) {?> checked="checked" <?php } ?>> <label for="myself">Myself</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="for_whome" id="someone" value="1" <?php if(isset($_POST['for_whome_text']) && $_POST['for_whome_text'] == 1) {?> checked="checked" <?php } ?>> <label for="someone">Someone else <span class="spouse">(spouse: family member)</span></label>
						</p>
						<p class="form-row form-row-first client_field"  id="client_first_name">
						<label for="reg_billing_first_name"><?php _e( 'Client First name', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="text" class="input-text" name="client_first_name" id="reg_client_first_name" value="<?php if ( ! empty( $_POST['client_f_name'] ) ) esc_attr_e( $_POST['client_f_name'] ); ?>" />
						</p>


						<p class="form-row form-row-last client_field" id="client_last_name">
						<label for="reg_billing_last_name"><?php _e( 'Client Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="text" class="input-text" name="client_last_name" id="reg_client_last_name" value="<?php if ( ! empty( $_POST['client_l_name'] ) ) esc_attr_e( $_POST['client_l_name'] ); ?>" />
						</p>
						</form>
					</div>
                    <?php the_content(); ?>
                    <input type="hidden" name="form_type" id="form_type" value="0">
                    <div class="step-pagination"><a href="javascript:void(0)" onclick="checkMe();" style="float:right;" class="btn" id="next_ste">Next</a></div> 
                    </div>
                    <div class="cont-right step1">
                    	<div class="widget widget_nav_menu" id="nav_menu-4">
                    		<h3>Help</h3>
                    		<div class="menu-learn-more-menu-container">
                    			<ul class="menu" id="menu-learn-more-menu">
                    				<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1099" id="menu-item-1099"><a href="#howtoorder">How to order <span class="icon-btn-right"></span></a></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1100" id="menu-item-1100"><a href="#howtotakehairsample">How to take hair sample <span class="icon-btn-right"></span></a></li>
									<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1101" id="menu-item-1101"><a href="#whattoexpect">What to expect <span class="icon-btn-right"></span></a></li>
								</ul>
							</div>
						</div>
                      <?php //dynamic_sidebar( 'steps-sidbar' ); ?>
                    </div>
                     <div class="popup-box">
                  	<div class="modal" id="howtoorder" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-header">
                    <h2>How to Order</h2>
                    <a href="#close" class="btn-close" aria-hidden="true">&#10006;</a> <!--CHANGED TO "#close"-->
                    </div>
                    <div class="modal-body">
                   
<h2><span style="font-weight: 600;">How to order</span></h2>
<p><strong>Ordering tests from Allergenics is simple:</strong></p>
<ol>
<li style="font-weight: 400;"><b>Choose one or more of our hair tests</a>.</b><span style="font-weight: 400;">&nbsp; You can add these to your cart by pressing the add to cart button. We offer four different tests:</span>
<ol>
<li style="font-weight: 400;"><span style="font-weight: 400;">Food and Environmental Sensitivity Assessment (**<em>our most popular test</em>).</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Vitamin and Mineral Assessment</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Organ Stress Assessment</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Heavy Metal and Toxic Element Assessment</span></li>
</ol>
</li>
<li style="font-weight: 400;"><b>Choose processing time. &nbsp;</b><span style="font-weight: 400;">When reviewing your order on the cart page you can also choose to have your tests processed urgently if you require faster turnaround times. </span></li>
<li style="font-weight: 400;"><b>Proceed to checkout </b><span style="font-weight: 400;">&nbsp;&ndash; &nbsp;provide your personal details, accept our terms and conditions and make payment.</span></li>
<li style="font-weight: 400;"><b>Health assessment form</b><span style="font-weight: 400;"> &ndash; &nbsp;you will then receive a summary of your order and link to our health assessment form to fill out. </span></li>
<li style="font-weight: 400;"><b>Send hair sample</b><span style="font-weight: 400;"> &ndash; &nbsp;finally don’t forget to send in your sample!</span></li>
</ol>
                    </div>
                    <div class="modal-footer">
                  
                    </div>
                    </div>
                    </div>

                    <div class="modal" id="howtotakehairsample" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-header">
                    <h2>How to take hair sample</h2>
                    <a href="#close" class="btn-close" aria-hidden="true">&#10006;</a> <!--CHANGED TO "#close"-->
                    </div>
                    <div class="modal-body">
                   <ul>
<li style="font-weight: 400;"><span style="font-weight: 400;">We need a minimum of a ½ teaspoon of hair to obtain accurate results.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Please take your hair sample from the back of your head.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Cut your hair as close the the scalp as possible and supply us with the sample that is closest to your scalp.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">Place the sample in a small paper envelope (not plastic).</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">If you have dyed hair and are doing a Heavy Metal test you will need to supply us with a sample of your pubic hair.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">If you have dyed hair and you are doing 2 or more tests including a Heavy Metal Test you will need to supply us with 2 samples (one head hair and one pubic hair) clearly labelled. </span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">If you do not dye your hair we only need one sample of head hair for your tests.</span></li>
<li style="font-weight: 400;"><span style="font-weight: 400;">If you do not have head hair please provide pubic hair. </span></li>
</ul>
                    </div>
                    <div class="modal-footer">
                 
                    </div>
                    </div>
                    </div>

                    <div class="modal" id="whattoexpect" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-header">
                    <h2>What to expect </h2>
                    <a href="#close" class="btn-close" aria-hidden="true">&#10006;</a> <!--CHANGED TO "#close"-->
                    </div>
                    <div class="modal-body">
                    <p><span style="font-weight: 400;">Once you have completed your health assessment form and we have received your hair sample, we will then go ahead and process your hair tests.</span></p>
<p><span style="font-weight: 400;">Your test results are then collated into a comprehensive report which you will receive in PDF form by email along with a personalised prescription if required.<br>
<a target="_blank" href="wp-content/uploads/2015/05/Report-for-Food-and-Environmental-Sensitivity-Sample-Test-.pdf">Download a free sample test report here</a>.</span></p>
<p><span style="font-weight: 400;">Our friendly team of Naturopaths are here to provide support and answer any questions you may have about your results. We are always happy to hear from you! Call us on: <a href="tel:0800004898">0800 004 898</a> or email us at <a href="mailto:info@allergenics.co.nz">info@allergenics.co.nz</a>.</span></p>

                    </div>
                    <div class="modal-footer">
                  
                    </div>
                    </div>
                    </div>
                </div>
                  </div>
                
                </section>
        <?php endwhile; ?>
	</div>
	
<?php    
	get_footer();
?> 