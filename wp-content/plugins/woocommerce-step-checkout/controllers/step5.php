<?php
include_once('front_template.php');
$obj= new Frontpage();
$obj->checkLogin();
	global $wpdb, $session;
	get_header();
	
?>
<div class="container">
        <div id="content">
			<div id="msform">
<!-- progressbar -->
	<ul id="progressbar">
		<li class="active">Personal Details</li>
		<li class="active">information form</li>
		<li class="active">Choose Test</li>
		<li class="active">Pay</li>
		<li class="active">Order Received</li>
	</ul>
	</div>
		<div><?php if(isset($_SESSION['msg'])) { echo $_SESSION['msg']; } ?></div>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_title( '<div class="title"><h1>', '</h1></div>' ); ?>
                <?php the_content(); ?>
                <?php edit_post_link( __( 'Edit', 'allergenics' ) ); ?>
            <?php endwhile; ?>
            <?php wp_link_pages(); ?>
            <?php comments_template(); ?>

        </div>
	</div>
<?php    
	get_footer();
?>