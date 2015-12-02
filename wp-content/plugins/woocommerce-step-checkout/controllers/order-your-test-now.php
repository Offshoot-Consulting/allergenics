<?php
include_once('front_template.php');
$obj= new Frontpage();
	global $wpdb;
	get_header();
	
?>
<div class="container">
        <div id="content">
	
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_title( '<div class="title"><h1>', '</h1></div>' ); ?>
			<?php //the_content(); ?>
               <div class="start_div">
               	<?php if ( is_user_logged_in() ) { ?>
               		<a class="btn" href="/step-2/">Start</a>
               		<?php } else { ?>    
               		<a class="btn" href="/step-1/">Start</a>
               		<?php } ?>
               </div>
            <?php endwhile; ?>
           
			
        </div>
	</div>
<?php    
	get_footer();
?>