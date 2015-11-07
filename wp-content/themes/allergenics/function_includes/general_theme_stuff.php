<?php
//general theme related stuff
add_action( 'themecheck_checks_loaded', 'theme_disable_cheks' );
function theme_disable_cheks() {
	$disabled_checks = array( 'TagCheck', 'Plugin_Territory', 'CustomCheck', 'EditorStyleCheck' );
	global $themechecks;
	foreach ( $themechecks as $key => $check ) {
		if ( is_object( $check ) && in_array( get_class( $check ), $disabled_checks ) ) {
			unset( $themechecks[$key] );
		}
	}
}

add_theme_support( 'automatic-feed-links' );

if ( !isset( $content_width ) ) {
	$content_width = 900;
}

remove_action( 'wp_head', 'wp_generator' );

add_action( 'after_setup_theme', 'theme_localization' );
function theme_localization () {
	load_theme_textdomain( 'allergenics', get_template_directory() . '/languages' );
}

/*
 * Let WordPress manage the document title.
 * By adding theme support, we declare that this theme does not use a
 * hard-coded <title> tag in the document head, and expect WordPress to
 * provide it for us.
 */
add_theme_support( 'title-tag' );

function theme_widget_init() {
	register_sidebar( array(
		'id'            => 'default-sidebar',
		'name'          => __( 'Default Sidebar', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'theme_widget_init' );

function theme_widget_init2() {
	register_sidebar( array(
		'id'            => 'contact-sidebar',
		'name'          => __( 'Order Form Sidebar', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'theme_widget_init2' );

function theme_widget_init3() {
	register_sidebar( array(
		'id'            => 'blog-sidebar',
		'name'          => __( 'Blog Sidebar', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'theme_widget_init3' );

function theme_widget_init4() {
	register_sidebar( array(
		'id'            => 'footer1-sidebar',
		'name'          => __( 'Footer Column 1', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer2-sidebar',
		'name'          => __( 'Footer Column 2', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'footer3-sidebar',
		'name'          => __( 'Footer Column 3', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'id'            => 'learn-more-sidebar',
		'name'          => __( 'Learn More Sidebar', 'allergenics' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'theme_widget_init4' );

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 50, 50, true ); // Normal post thumbnails
add_image_size( 'thumbnail_400x9999', 400, 9999, true );
add_image_size( 'thumbnail_1600x583', 1600, 583, true );
add_image_size( 'thumbnail_246x246', 246, 246, true );
add_image_size( 'thumbnail_97x75', 97, 75, true );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'allergenics' ),
	'footer_nav' => __( 'Footer Navigation', 'allergenics' ),
) );

//Add [email]...[/email] shortcode
function shortcode_email( $atts, $content ) {
	return antispambot( $content );
}
add_shortcode( 'email', 'shortcode_email' );

//Register tag [template-url]
function filter_template_url( $text ) {
	return str_replace( '[template-url]', get_template_directory_uri(), $text );
}
add_filter( 'the_content', 'filter_template_url' );
add_filter( 'widget_text', 'filter_template_url' );

//Register tag [site-url]
function filter_site_url( $text ) {
	return str_replace( '[site-url]', home_url(), $text );
}
add_filter( 'the_content', 'filter_site_url' );
add_filter( 'widget_text', 'filter_site_url' );

if( class_exists( 'acf' ) && !is_admin() ) {
	add_filter( 'acf/load_value', 'filter_template_url' );
	add_filter( 'acf/load_value', 'filter_site_url' );
}

//Replace standard wp menu classes
function change_menu_classes( $css_classes ) {
	return str_replace( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ), 'active', $css_classes );
}
add_filter( 'nav_menu_css_class', 'change_menu_classes' );

//Allow tags in category description
$filters = array( 'pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description' );
foreach ( $filters as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
}

//Make wp admin menu html valid
function wp_admin_bar_valid_search_menu( $wp_admin_bar ) {
	if ( is_admin() )
		return;

	$form  = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="adminbarsearch"><div>';
	$form .= '<input class="adminbar-input" name="s" id="adminbar-search" tabindex="10" type="text" value="" maxlength="150" />';
	$form .= '<input type="submit" class="adminbar-button" value="' . __( 'Search', 'allergenics' ) . '"/>';
	$form .= '</div></form>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'search',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

function fix_admin_menu_search() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
	add_action( 'admin_bar_menu', 'wp_admin_bar_valid_search_menu', 4 );
}
add_action( 'add_admin_bar_menus', 'fix_admin_menu_search' );

//Disable comments on pages by default
function theme_page_comment_status( $post_ID, $post, $update ) {
	if ( !$update ) {
		remove_action( 'save_post_page', 'theme_page_comment_status', 10 );
		wp_update_post( array(
			'ID' => $post->ID,
			'comment_status' => 'closed',
		) );
		add_action( 'save_post_page', 'theme_page_comment_status', 10, 3 );
	}
}
add_action( 'save_post_page', 'theme_page_comment_status', 10, 3 );

//custom excerpt
function theme_the_excerpt() {
	global $post;
	
	if ( trim( $post->post_excerpt ) ) {
		the_excerpt();
	} elseif ( strpos( $post->post_content, '<!--more-->' ) !== false ) {
		the_content();
	} else {
		the_excerpt();
	}
}

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

//theme password form
function theme_get_the_password_form() {
	global $post;
	$post = get_post( $post );
	$label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
	<p>' . __( 'This content is password protected. To view it please enter your password below:', 'allergenics' ) . '</p>
	<p><label for="' . $label . '">' . __( 'Password:', 'allergenics' ) . '</label> <input name="post_password" id="' . $label . '" type="password" size="20" /> <input type="submit" name="Submit" value="' . esc_attr__( 'Submit' ) . '" /></p></form>
	';
	return $output;
}
add_filter( 'the_password_form', 'theme_get_the_password_form' );

function base_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	wp_deregister_script( 'comment-reply' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', get_template_directory_uri() . '/js/comment-reply.js' );
	}

	// Loads JavaScript file with functionality specific.
	wp_enqueue_script( 'base-script', get_template_directory_uri() . '/js/jquery.main.js', array( 'jquery' ) );

	// Loads our main stylesheet.
	wp_enqueue_style( 'base-style', get_stylesheet_uri(), array() );
	wp_enqueue_style( 'font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700', array() );
	
	// Implementation stylesheet.
	wp_enqueue_style( 'base-theme', get_template_directory_uri() . '/theme.css', array() );	

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'base-ie', get_template_directory_uri() . '/css/ie.css' );
	wp_style_add_data( 'base-ie', 'conditional', 'IE 9' );
}
add_action( 'wp_enqueue_scripts', 'base_scripts_styles' );

//theme options tab in appearance
if( function_exists( 'acf_add_options_sub_page' ) ) {
	acf_add_options_sub_page( array(
		'title'  => 'Theme Options',
		'parent' => 'themes.php',
	) );
}

//acf theme functions placeholders
if( !class_exists( 'acf' ) && !is_admin() ) {
	function get_field_reference( $field_name, $post_id ) { return ''; }
	function get_field_objects( $post_id = false, $options = array() ) { return false; }
	function get_fields( $post_id = false ) { return false; }
	function get_field( $field_key, $post_id = false, $format_value = true )  { return false; }
	function get_field_object( $field_key, $post_id = false, $options = array() ) { return false; }
	function the_field( $field_name, $post_id = false ) {}
	function have_rows( $field_name, $post_id = false ) { return false; }
	function the_row() {}
	function reset_rows( $hard_reset = false ) {}
	function has_sub_field( $field_name, $post_id = false ) { return false; }
	function get_sub_field( $field_name ) { return false; }
	function the_sub_field( $field_name ) {}
	function get_sub_field_object( $child_name ) { return false;}
	function acf_get_child_field_from_parent_field( $child_name, $parent ) { return false; }
	function register_field_group( $array ) {}
	function get_row_layout() { return false; }
	function acf_form_head() {}
	function acf_form( $options = array() ) {}
	function update_field( $field_key, $value, $post_id = false ) { return false; }
	function delete_field( $field_name, $post_id ) {}
	function create_field( $field ) {}
	function reset_the_repeater_field() {}
	function the_repeater_field( $field_name, $post_id = false ) { return false; }
	function the_flexible_field( $field_name, $post_id = false ) { return false; }
	function acf_filter_post_id( $post_id ) { return $post_id; }
}

// blog related stuff

add_post_type_support( 'page', 'excerpt' );   
function new_excerpt_more( $more ) {
 return '&hellip;';
}
add_filter('excerpt_more', 'new_excerpt_more');

add_image_size( 'listing-thumb', 480, 320, array( 'center', 'center' ) );
add_image_size( 'single-thumb', 800, 270, array( 'center', 'center' ) );

/* Change Faq page */
remove_shortcode( 'colorful_faq' );
remove_shortcode( 'nested_faq' );
add_shortcode( 'colorful_faq', 'faq_func_cc' );
add_shortcode( 'nested_faq', 'faq_func_cc' );

function faq_func_cc($atts) {
	
	$posts_per_page = get_faq_admin_data('posts_per_page', 'colorful_faq_general' );
	extract( shortcode_atts(
		array(
			'items' => $posts_per_page,
			'cat' => '',
			'tag' => '',
			'orderby' => 'menu_order title',
			'order'   => 'ASC',
			), $atts )
	);
	
	$args = array();
	$args['post_type'] = 'faq';
	$args['posts_per_page'] = $posts_per_page;
	//$args['order'] = $order;
	if($cat != '0') {
	$category = get_term_by('slug', $cat, 'faq_cat');
	$val = time() . rand();
	$time = time();	 
	$args['tax_query'][] = array('taxonomy' => 'faq_cat','field' => 'id' ,'terms' => $category->term_id);
	}
	else {
		$val = time() . rand();
		$time = time();	
	}
		
	$accordion = 'accordion-' . time() . rand();
	// The Query
	$Query = new WP_Query( $args );
	
	$cat_div_id = 'nested_accordion'.time().rand()
	?>
    
    <div id="awesome-colorful-faq" style="margin-bottom:20px;">
		<div class="panel-group" id="<?php echo $cat_div_id; ?>">
			<div class="panel panel-default">

						<?php if($cat === '0') { ?>
								<div class="panel-heading" style="background:#e2e2e2;">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#<?php echo $cat_div_id; ?>" href="#<?php echo $cat_div_id; ?>-<?php echo $cat ?>" class="" style="color:#444444;">
											<span class="pull-right icon"></span>
											All Posts										</a>
									</h4>
								</div>
                                <?php } else { ?>
                                <div class="panel-heading" style="background:#e2e2e2;">
									<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#<?php echo $cat_div_id; ?>" href="#<?php echo $cat_div_id; ?>-<?php echo $category->term_id; ?>" class="collapsed" style="color:#444444;">
											<span class="pull-right icon"></span>
											<?php echo $category->name; ?>										</a>
									</h4>
								</div>
                                <?php } ?>
<?php if($cat != 0) { ?>
								<div id="<?php echo $cat_div_id; ?>-<?php echo $category->term_id; ?>" class="panel-collapse collapse" style="background:#ffffff; color:#444; border-color:;">
                                <?php } else { ?>
                                <div id="<?php echo $cat_div_id; ?>-<?php echo $cat; ?>" class="panel-collapse collapse" style="background:#ffffff; color:#444; border-color:;">
                                <?php } ?>
									<div class="panel-body">
											<div id="awesome-colorful-faq">
                                            <?php $count = 0; $accordion = 'accordion-' . time().rand(); ?>
		<div class="panel-group" id="<?php echo $accordion.$count; ?>">
		<?php  while ( $Query->have_posts() ) {
				$Query->the_post(); ?>
		
			<div class="panel panel-default">
				<div class="panel-heading" style="background:#e2e2e2;">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#<?php echo $accordion.$count; ?>" href="#<?php echo $accordion; ?>-<?php echo get_the_ID(); ?>" style="color:#444444;" class="collapsed">
							<span class="pull-right icon"></span>
							<?php the_title();?>						</a>
					</h4>
				</div>
				<div id="<?php echo $accordion; ?>-<?php echo get_the_ID(); ?>" class="panel-collapse collapse" style="background:#ffffff; color:#444; border-color:; height:0px;">
					<div class="panel-body">					
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		
		<?php $count++; }  ?>

		</div>
</div><!-- /#awesome-colorful-faq -->


									</div>
								</div>

							
			</div>
		</div>
</div>
<?php } ob_start(); ob_flush();




?>
