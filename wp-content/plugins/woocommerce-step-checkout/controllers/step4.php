<?php
include_once('front_template.php');
$obj= new Frontpage();
$obj->checkLogin();
if(isset($_GET['key']) && $_GET['key'] !='') {
$_SESSION['checkout'] = 'Done';
}
else {
$obj->steps();
}
$obj->step4();

	global $wpdb;
	get_header();
 
?>
<?php if(isset($_SESSION["form_completed"]) && $_SESSION["form_completed"] == 'true') { ?>
<script type="text/javascript">
jQuery(window).load(function() {
step4_js('<?php echo $_SESSION["form_completed"]; ?>');

});
</script>
<style type="text/css">
    .whats-next .step02 { background: rgba(0, 0, 0, 0) url("<?php echo plugin_dir_url( __FILE__ ); ?>picon5.png") no-repeat scroll 10px 0; }
</style>
<?php } else { ?>
<script type="text/javascript">
jQuery(window).load(function() {
var html = '<p style="margin-top:10px;font-weight: bold;">You can send your hair sample by post to</p><p style="font-weight: bold;">PO BOX 60 156, Titirangi, Auckland<br>or by courier to c/o Titirangi Pharmacy 408 Titirangi Rd<br></p>';

                jQuery('.order-one .order-right').append(html);
});
</script>
<?php } ?>
<?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage woocommerce-page woocommerce">
                  <div class="container clearfix">
                    <?php if(!isset($_GET['key'])) { ?>
                    <h1><?php //the_title(); ?>Order your test now</h1>
                    <!-- progressbar -->
                    <div id="msform">

                        <ul id="progressbar">
                        <li class="active">Personal Details</li>
                        <li class="active">Health Assessment</li>
                        <li class="active">Choose Test</li>
                        <li class="active">Pay</li>
                    
                        </ul>

                    </div>
                    <?php } else { ?>

                    <?php } ?>
            
                    <?php the_content(); ?>
                    <?php if(!isset($_GET['key'])) { ?>
                    <div class="step-pagination-steps4"><a href="<?php echo home_url('/step-3'); ?>" style="float:left;" class=""><< Edit Order</a></div>
                    <?php } ?>
              
                  </div>
                </section>
        <?php endwhile; ?>
<?php    
	get_footer();
?>