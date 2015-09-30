<?php 




/*--------------------------------------------------------------
 *				Add Sub-Menu Admin Style
 *-------------------------------------------------------------*/

function jwt_a_f_p_posts_sort_styles()
{
	$screen = get_current_screen();
	
	if($screen->post_type == 'faq')
	{
		wp_enqueue_style( 'sort-stylesheet', plugin_dir_url( __FILE__ ) . 'css/sort-stylesheet.css', array(), false, false );		
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'sort-script', plugin_dir_url( __FILE__ ) .  'js/sort-script.js' , array(), false, true );
	}	

}

add_action( 'admin_print_styles', 'jwt_a_f_p_posts_sort_styles' );


/*--------------------------------------------------------------
 *					Add Submenu for all Post Types
 *-------------------------------------------------------------*/

//FAQ Submenu
function jwt_a_f_p_sort_posts(){
    add_submenu_page('edit.php?post_type=faq', 'Sort FAQ', 'Sort FAQ', 'edit_posts', basename(__FILE__), 'jwt_a_f_p_posts_sort_callback');
}

add_action('admin_menu' , 'jwt_a_f_p_sort_posts');


function jwt_a_f_p_posts_sort_callback(){

	$faq = new WP_Query('post_type=faq&posts_per_page=-1&orderby=menu_order&order=ASC');
?>
	<div class="wrap">
		<h3>Sort FAQ<img src="<?php echo home_url(); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h3>
		<ul id="slide-list">
			<?php if($faq->have_posts()): ?>
				<?php while ( $faq->have_posts() ){ $faq->the_post(); ?>
					<li id="<?php the_id(); ?>"><?php the_title(); ?></li>			
				<?php } ?>
			<?php else: ?>
				<li>There is no FAQ was Created !!!</li>		
			<?php endif; ?>
		</ul>
	</div>
<?php
}



/*--------------------------------------------------------------
 *				Ajax Call-back
 *-------------------------------------------------------------*/

function jwt_a_f_p_posts_sort_order()
{
	global $wpdb; // WordPress database class

	$order = explode(',', $_POST['order']);
	$counter = 0;
	
	foreach ($order as $slide_id) {
		$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $slide_id) );
		$counter++;
	}
	die(1);
}

add_action('wp_ajax_team_sort', 'jwt_a_f_p_posts_sort_order');