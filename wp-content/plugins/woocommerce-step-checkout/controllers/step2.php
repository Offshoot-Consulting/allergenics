<?php
include_once('front_template.php');
$obj= new Frontpage();
$obj->checkLogin();
$obj->steps();
$obj->step2();
	global $wpdb,$current_user;
	get_header();
    get_currentuserinfo();
	$user_ID = get_current_user_id();
	$client_first_name = get_user_meta( $user_ID, 'client_first_name', true ); 
	$client_last_name = get_user_meta( $user_ID, 'client_last_name', true );
    if($client_last_name != '' && $client_last_name != '') {

    } 
    else {
        $client_first_name = $current_user->user_firstname;
        $client_last_name = $current_user->user_lastname;
    }
    $customer_first_name = $current_user->user_firstname;
    $customer_last_name = $current_user->user_lastname ;
   
?>
<?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage woocommerce">
                  <div class="container clearfix">
                    <div class="cont-left steps_left">
                    <h1><?php //the_title(); ?>Order your test now</h1>
                    <!-- progressbar -->
                    <div id="msform">

                        <ul id="progressbar">
                        <li class="active">Personal Details</li>
                        <li class="active">Health Assessment</li>
                        <li>Choose Test</li>
                        <li>Pay</li>
                    
                        </ul>

                    </div>
                    	<?php if(isset($_SESSION["form_completed"]) && $_SESSION["form_completed"] == 'true') {
                	?>
<h3 style="text-align:center; color:#000;">You're Health Assessment form has already been completed.</h3>
                	<?php
                }
                else {
                   
					$url = 'https://allergenics.typeform.com/to/fSPsMs?wcuserid='.$user_ID.'&client_first_name='.$client_first_name.'&client_last_name='.$client_last_name.'&customer_first_name='.$customer_first_name.'&customer_last_name='.$customer_last_name;  
				?>
                <?php if($_SESSION['checkout'] == 'Done') { ?>
                <div class="selection_step1 woocommerce" id="step_client_info">
                        <form>
                        <p class="form-row form-row-wide first_p">
                        <label for="for_whome">Who is this test for? <span class="required">*</span></label>
                        </p>
                        <p class="form-row form-row-wide second_p">
                        <input type="radio" checked="checked" value="0" id="myself" name="for_whome"> <label for="myself">Myself</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="1" id="someone" name="for_whome"> <label for="someone">Someone else <span class="spouse">(spouse: family member)</span></label>
                        </p>
                        <p id="client_first_name" class="form-row form-row-first client_field" style="display: none;">
                        <label for="reg_billing_first_name">Client First name <span class="required">*</span></label>
                        <input type="text" value="" id="reg_client_first_name" name="client_first_name" class="input-text">
                        </p>


                        <p id="client_last_name" class="form-row form-row-last client_field" style="display: none;">
                        <label for="reg_billing_last_name">Client Last name <span class="required">*</span></label>
                        <input type="text" value="" id="reg_client_last_name" name="client_last_name" class="input-text">
                        </p>
                        <p class="form-row form-row-first" id="client_info_save" style="display: none;">
                            <a href="javascript:void(0);" class="btn" onclick="change_client_info()">Save</a>
                        </p>
                        </form>
                    </div>
                <?php } ?>
                <div class="assesment_form">
                <h3>Please fill out Health Assessment questionnaire which will give us a better picture of your state of health.</h3>
                <h4>Questions should be answered for the person the test is being done for.</h4>
          <a class="typeform-share button" href="<?php echo $url; ?>" data-mode="1" target="_blank">Start</a>
<script>(function(){var qs,js,q,s,d=document,gi=d.getElementById,ce=d.createElement,gt=d.getElementsByTagName,id='typef_orm',b='https://s3-eu-west-1.amazonaws.com/share.typeform.com/';if(!gi.call(d,id)){js=ce.call(d,'script');js.id=id;js.src=b+'share.js';q=gt.call(d,'script')[0];q.parentNode.insertBefore(js,q)}id=id+'_';if(!gi.call(d,id)){qs=ce.call(d,'link');qs.rel='stylesheet';qs.id=id;qs.href=b+'share-button.css';s=gt.call(d,'head')[0];s.appendChild(qs,s)}})()</script>
</div>
<?php } ?>
                    <?php the_content(); ?>
                    <?php if(get_option( '_skip_step_2') == 0 && get_option( '_skip_step_2_admin') == 1 &&  current_user_can( 'manage_options' ) ) { ?>
                        <div class="step-pagination" style="margin-top:50px;"><a href="<?php echo home_url('/step-3'); ?>" class="skip_link">Skip this step</a></div> 
                    <?php } ?>
                    <div class="step-pagination" style="margin-top:50px;"><?php if(!isset($_SESSION["form_completed"]) && get_option( '_skip_step_2') == 1)  { ?><a href="<?php echo home_url('/step-3'); ?>" class="skip_link">Skip this step</a><?php } ?><?php if(isset($_SESSION["form_completed"]) && $_SESSION["form_completed"] == 'true') { ?><a href="<?php echo home_url('/step-3'); ?>"" style="float:right;" class="btn">Next</a><?php } ?></div> 
                    </div>
                    <div class="cont-right">
                      <?php dynamic_sidebar( 'steps-sidbar' ); ?>
                    </div>
                  </div>
                </section>
        <?php endwhile; ?>
<?php    
	get_footer();
?>