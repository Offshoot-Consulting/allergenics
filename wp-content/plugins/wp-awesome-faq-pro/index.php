<?php
/**
 * Plugin Name: Awesome Colorful FAQ PRO
 * Plugin URI: http://jeweltheme.com/product/wp-awesome-faq-pro/
 * Description: Awesome Colorful FAQ WordPress plugin help you to build awesome colorful FAQ (Frequently Asked Question) page in wordpress site. A plugin developed by <a href="http://www.jeweltheme.com/">jeweltheme</a>.
 * Version: 2.0.0
 * Author: Jewel Theme
 * Author URI: http://jeweltheme.com
 * Text Domain: jeweltheme
 */

/*
 * Include Settings Page
 */

include( plugin_dir_path( __FILE__ ) . 'admin/class.settings-api.php');
include( plugin_dir_path( __FILE__ ) . 'admin/colorful-faq-settings.php');

//Sorting
include( plugin_dir_path( __FILE__ ) . 'lib/sorting.php');

// Load shortcode generator files
include( plugin_dir_path( __FILE__ ) . 'lib/tinymce.button.php');

/*
 * Creating custom cost type to  adding FAQs.
 */
function jwt_a_f_p_post_type() {

	// Register FAQs Post Type
	$labels = array(
		'name'                => _x( 'FAQs', 'jeweltheme' ),
		'singular_name'       => _x( 'FAQ', 'jeweltheme' ),
		'menu_name'           => __( 'FAQs', 'jeweltheme' ),
		'parent_item_colon'   => __( 'Parent FAQs:', 'jeweltheme' ),
		'all_items'           => __( 'All FAQs', 'jeweltheme' ),
		'view_item'           => __( 'View FAQ', 'jeweltheme' ),
		'add_new_item'        => __( 'Add New FAQ', 'jeweltheme' ),
		'add_new'             => __( 'New FAQ', 'jeweltheme' ),
		'edit_item'           => __( 'Edit FAQ', 'jeweltheme' ),
		'update_item'         => __( 'Update FAQ', 'jeweltheme' ),
		'search_items'        => __( 'Search FAQs', 'jeweltheme' ),
		'not_found'           => __( 'No FAQs found', 'jeweltheme' ),
		'not_found_in_trash'  => __( 'No FAQs found in Trash', 'jeweltheme' ),
		);
	$args = array(
		'label'               => __( 'FAQ', 'jeweltheme' ),
		'description'         => __( 'Jewel Theme FAQ Post Type', 'jeweltheme' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => true,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		);
	register_post_type( 'faq', $args );

	//Register Category Taxonomy FAQs
	register_taxonomy( 'faq_cat', 'faq', array(
		'labels'                     =>  __( 'Categories', 'jeweltheme' ),
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		) );

	// Register Tags Taxonomy FAQs
	register_taxonomy( 'faq_tags', 'faq', array(
		'labels'                     => __( 'Tags', 'jeweltheme' ),
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		) );
}

// Hook into the 'init' action
add_action( 'init', 'jwt_a_f_p_post_type', 0 );


// Add FAQs icon in dashboard
function jwt_a_f_p_dashboard_icon(){
	?>
	<style>
		/*FAQs Dashboard Icons*/
		#adminmenu .menu-icon-faq div.wp-menu-image:before {
			content: "\f348";
		}
	</style>
	<?php
}
add_action( 'admin_head', 'jwt_a_f_p_dashboard_icon' );

/*
* Load Script When adding new post
*/

function jwt_a_f_p_admin_script() {
	global $typenow;
	if( $typenow == 'faq' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'awesome-faq-pro', plugins_url('/assets/js/proscript.js', __FILE__), array('wp-color-picker'), '1.0.0', 'all'  );
	}
}
add_action( 'admin_enqueue_scripts', 'jwt_a_f_p_admin_script' );

/*
* Add Meta Box In Colorful FAQ Post Type
*/

function jwt_a_f_p_customizer_meta_box() {
	add_meta_box( 'jw_faq_customizer', __( 'FAQ Customizer', 'jeweltheme' ), 'jwt_a_f_p_customizer_callback', 'jw_faq','side','high' );
}
add_action( 'add_meta_boxes', 'jwt_a_f_p_customizer_meta_box' );

/*
* FAQ Customizer Fields
*/

function jwt_a_f_p_customizer_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'awesome_faq_customizer_nonce' );
	$ccr_store_data = get_post_meta( $post->ID );
	?>

	<h3 class="faq-customizer-title"><?php _e( 'Title Background Color', 'jeweltheme' )?></h3>

	<p>
		<input name="faq-title-bg-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-title-bg-color'] ) ) echo $ccr_store_data['faq-title-bg-color'][0]; ?>" class="faq-color-picker" />
	</p>

	<h3 class="faq-customizer-title"><?php _e( 'Title Color', 'jeweltheme' )?></h3>

	<p>
		<input name="faq-title-text-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-title-text-color'] ) ) echo $ccr_store_data['faq-title-text-color'][0]; ?>" class="faq-color-picker" />
	</p>

	<h3 class="faq-customizer-title"><?php _e( 'Content Background Color', 'jeweltheme' )?></h3>
	
	<p>
		<input name="faq-bg-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-bg-color'] ) ) echo $ccr_store_data['faq-bg-color'][0]; ?>" class="faq-color-picker" />
	</p>

	<h3 class="faq-customizer-title"><?php _e( 'Content Text Color', 'jeweltheme' )?></h3>
	
	<p>
		<input name="faq-text-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-text-color'] ) ) echo $ccr_store_data['faq-text-color'][0]; ?>" class="faq-color-picker" />
	</p>

	<h3 class="faq-customizer-title"><?php _e( 'Content Border Color', 'jeweltheme' )?></h3>
	
	<p>
		<input name="faq-border-color" type="text" value="<?php if ( isset ( $ccr_store_data['faq-border-color'] ) ) echo $ccr_store_data['faq-border-color'][0]; ?>" class="faq-color-picker" />
	</p>

	<?php
}

/*
* FAQ Customizer Data Save
*/

function jwt_a_f_p_customizer_data_save( $post_id ) {

	// Checks faq post save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'awesome_faq_customizer_nonce' ] ) && wp_verify_nonce( $_POST[ 'awesome_faq_customizer_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	// Checks for title background color input and saves if needed
	if( isset( $_POST[ 'faq-title-bg-color' ] ) ) {
		update_post_meta( $post_id, 'faq-title-bg-color', $_POST[ 'faq-title-bg-color' ] );
	}

	// Checks for title text color and saves if needed
	if( isset( $_POST[ 'faq-title-text-color' ] ) ) {
		update_post_meta( $post_id, 'faq-title-text-color', $_POST[ 'faq-title-text-color' ] );
	}

	// Checks for faq background and saves if needed
	if( isset( $_POST[ 'faq-bg-color' ] ) ) {
		update_post_meta( $post_id, 'faq-bg-color', $_POST[ 'faq-bg-color' ] );
	}

	// Checks for faq text color and saves if needed
	if( isset( $_POST[ 'faq-text-color' ] ) ) {
		update_post_meta( $post_id, 'faq-text-color', $_POST[ 'faq-text-color' ] );
	}

	// Checks for faq border color and saves if needed
	if( isset( $_POST[ 'faq-border-color' ] ) ) {
		update_post_meta( $post_id, 'faq-border-color', $_POST[ 'faq-border-color' ] );
	}

}
add_action( 'save_post', 'jwt_a_f_p_customizer_data_save' );

/*
* Get Render the data from admin panel and post meta
*/

/* 
 * Get Options Settings 
 */


// To rendering data from admin panel

function get_faq_admin_data( $option, $section, $default = '' ) {

	$options = get_option( $section );

	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}

	return $default;
}


// This function for render FAQ title background color
function jw_faq_title_bg_color() {
	if ( get_post_meta( get_the_ID(), 'faq-title-bg-color', true ) ) {
		echo get_post_meta( get_the_ID(), 'faq-title-bg-color', true );
	} else {
		echo get_faq_admin_data('faq-title-bg-color', 'colorful_faq_general' );
	}
}

// This function for render FAQ title text color
function jw_faq_title_text_color() {
	if ( get_post_meta( get_the_ID(), 'faq-title-text-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-title-text-color', true );

	} else {
		echo get_faq_admin_data('faq-title-text-color', 'colorful_faq_general' );
	}
}

// This function for render FAQ content background color
function jw_faq_bg_color() {
	if ( get_post_meta( get_the_ID(), 'faq-bg-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-bg-color', true );

	} else {
		echo get_faq_admin_data('faq-bg-color', 'colorful_faq_general' );
	}
}

// This function for render FAQ Content Text color
function jw_faq_text_color() {
	if ( get_post_meta( get_the_ID(), 'faq-text-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-text-color', true );

	} else {
		echo get_faq_admin_data('faq-text-color', 'colorful_faq_general' );
	}
}

// This function for render FAQ border color
function jw_faq_border_color() {
	if ( get_post_meta( get_the_ID(), 'faq-border-color', true ) ) {

		echo get_post_meta( get_the_ID(), 'faq-border-color', true );

	} else {
		echo get_faq_admin_data('faq-border-color', 'colorful_faq_general' );
	}
}

/*
 * Enqueue Bootstrap According JS and Styleseets
 */

function jw_faq_load_script_style() {
	wp_enqueue_script('jquery' );
	wp_enqueue_style( 'awesome-faq-style', plugins_url('/assets/css/bootstrap.css', __FILE__), array(), '1.0.0', 'all' );
	wp_enqueue_script( 'awesome-faq-bs-script', plugins_url('/assets/js/bootstrap.min.js', __FILE__), array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'jw_faq_load_script_style' );

/*
 * FAQ Post Query And Short Code
 */

function colorful_faq( $atts , $content = null ) {

$posts_per_page = get_faq_admin_data('posts_per_page', 'colorful_faq_general' );

ob_start();


	extract( shortcode_atts(
		array(
			'items' => $posts_per_page,
			'cat' => '',
			'tag' => '',
			'orderby' => 'menu_order title',
			'order'   => 'ASC',
			), $atts )
	);

	// WP_Query arguments
	$args = array (
		'post_type'              => 'faq',
		'faq_cat'          		 => $cat,
		'faq_tags'               => $tag,
		'posts_per_page'         => $items,
		'order'                  => $order,
		);

	// The Query
	$faqQuery = new WP_Query( $args );

	//First Post Active
	$count = 0; 
	$accordion = 'accordion-' . time() . rand();

	?>
	<div id="awesome-colorful-faq">
		<div class="panel-group" id="<?php echo $accordion .  $count;?>">
		
		<?php // The Loop
		if ( $faqQuery->have_posts() ) {
			while ( $faqQuery->have_posts() ) {
				$faqQuery->the_post();
				?>

			<div class="panel panel-default">
				<div class="panel-heading" style="background:<?php jw_faq_title_bg_color(); ?>;" >
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#<?php echo $accordion .  $count;?>" href="#<?php echo $accordion;?>-<?php the_ID(); ?>" style="color:<?php jw_faq_title_text_color(); ?>;">
							<span class="pull-right icon"></span>
							<?php the_title() ?>
						</a>
					</h4>
				</div>
				<div id="<?php echo $accordion;?>-<?php the_ID(); ?>" class="panel-collapse in" style="background:<?php jw_faq_bg_color(); ?>; color:<?php jw_faq_text_color(); ?>; border-color:<?php jw_faq_border_color(); ?>;">
					<div class="panel-body">					
						<?php the_content(); ?>
					</div>
				</div>
			</div>

		<?php 
		 $count ++;
		 } } ?>
</div>
</div><!-- /#awesome-colorful-faq -->

<?php
wp_reset_query();
wp_reset_postdata();


    $output = ob_get_contents(); // end output buffering
    ob_end_clean(); // grab the buffer contents and empty the buffer
    return $output;
}

add_shortcode( 'colorful_faq', 'colorful_faq' );



// Nested FAQ Items

function jeweltheme_nested_colorful_faq( $atts , $content = null ) {

$posts_per_page = get_faq_admin_data('posts_per_page', 'colorful_faq_general' );

ob_start();


	extract( shortcode_atts(
		array(
			'items' => $posts_per_page,
			'cat' => '',
			'tag' => '',
			'orderby' => 'menu_order title',
			'order'   => 'ASC',
			), $atts )
	);

	// WP_Query arguments
	$nested_args = array (
		'post_type'              => 'faq',
		'faq_cat'          => $cat,
		'faq_tags'               => $tag,
		'posts_per_page'         => $items,
		'order'                  => $order,
		);

	// The Query
	$faq_nested_query = new WP_Query( $nested_args );


	$nested_accordion_id = 'nested_accordion' . time() . rand();
	?>

	<div id="awesome-colorful-faq">
		<div class="panel-group" id="<?php echo $nested_accordion_id;?>">
			<div class="panel panel-default">
	
				<?php 
					if ( $faq_nested_query->have_posts() ) {
						while ( $faq_nested_query->have_posts() ) {
							$faq_nested_query->the_post(); ?>

								<div class="panel-heading" style="background:<?php jw_faq_title_bg_color(); ?>;">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#<?php echo $nested_accordion_id;?>" href="#<?php echo $nested_accordion_id;?>-<?php the_ID(); ?>" class="collapsed" style="color:<?php jw_faq_title_text_color(); ?>;">
											<span class="pull-right icon"></span>
											<?php the_title() ?>
										</a>
									</h4>
								</div>

								<div id="<?php echo $nested_accordion_id;?>-<?php the_ID(); ?>" class="panel-collapse collapse" style="background:<?php jw_faq_bg_color(); ?>; color:<?php jw_faq_text_color(); ?>; border-color:<?php jw_faq_border_color(); ?>;">
									<div class="panel-body">
										<?php the_content(); ?>
									</div>
								</div>

							<?php 
						} 
					} 
				?>

			</div>
		</div>
	</div>


<?php
wp_reset_query();
wp_reset_postdata();


    $output = ob_get_contents(); // end output buffering
    ob_end_clean(); // grab the buffer contents and empty the buffer
    return $output;
}

add_shortcode( 'nested_faq', 'jeweltheme_nested_colorful_faq' );

