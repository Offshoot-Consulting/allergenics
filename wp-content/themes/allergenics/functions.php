<?php

//Staging restrictions
if ( file_exists( sys_get_temp_dir() . '/staging-restrictions.php' ) ) {
	define( 'STAGING_RESTRICTIONS', true );
	require_once sys_get_temp_dir() . '/staging-restrictions.php';
}

if (!session_id()) {
    session_start();
}
include( get_template_directory() . '/widgets.php' );

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
	wp_enqueue_style( 'font-open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700', array() );
	
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

add_post_type_support( 'page', 'excerpt' );

function new_excerpt_more( $more ) {
 return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


add_action("gform_post_submission", "set_post_content", 10, 2);
 function set_post_content($entry, $form){
 //Gravity Forms has validated the data
 //Our Custom Form Submitted via PHP will go here
 // Lets get the IDs of the relevant fields and prepare an email message
 $message = print_r($entry, true);
 // In case any of our lines are larger than 70 characters, we should use wordwrap()
 $message = wordwrap($message, 70);
 mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Fields', $message);
 // Send

	$fld_user_name = 'mt_user_name';
	$fld_pwd = 'mt_pwd';	
	$fld_user_name2 = 'mt_user_name2';
	$fld_pwd2 = 'mt_pwd2';


    	$fld_ccform_url = 'mt_ccform_url';
	$fld_succ_url = 'mt_succ_url';	
	$fld_fail_url = 'mt_fail_url';	
	
	$fld_user_name_val = get_option( $fld_user_name );
 	$fld_pwd_val = get_option( $fld_pwd );
 	$fld_user_name_val2 = get_option( $fld_user_name2 );
 	$fld_pwd_val2 = get_option( $fld_pwd2 );
	$_SESSION['a2aid']=$fld_user_name_val2;
	$_SESSION['a2akey']=$fld_pwd_val2;
	
	//$english_format_number = number_format($entry[59]);
	$_SESSION['amnt']=  number_format($entry[59], 2, '.', '');
	$_SESSION['fstname']=$entry['6.3'];
	$_SESSION['lstname']=$entry['6.6'];
	
 	$fld_ccform_url_val = get_option( $fld_ccform_url );
	$fld_succ_url_val = get_option( $fld_succ_url);	
	$fld_fail_url_val = get_option( $fld_fail_url );
	$_SESSION['ccformurl']=$fld_ccform_url_val;
	$plugin_dir = ABSPATH . 'wp-content/plugins/paymentexpress/';
	require_once $plugin_dir.'PxFusion.php';
	
	$pxf = new PxFusion($fld_user_name_val,$fld_pwd_val);
	
//$returnUrl = 'https://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/return.php';
$http_host   = $_SERVER['HTTP_HOST'];
$server_url  = "https://$http_host";	
//$returnUrl ="https://allergenicstesting.com/wp-content/plugins/paymentexpress/return.php";
$returnUrl =$server_url."/wp-content/plugins/paymentexpress/return.php";
//mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 1',$returnUrl);
	$pxf->set_txn_detail('txnType', 'Purchase');	# required
	$pxf->set_txn_detail('currency', 'NZD');		# required
	$pxf->set_txn_detail('returnUrl', $returnUrl);	# required
	$pxf->set_txn_detail('amount',$_SESSION['amnt']);		# required
        //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 0', $returnUrl);	

	$pxf->set_txn_detail('merchantReference', $_SESSION['fstname'].'_'.$_SESSION['lstname']);
        	
	// Some of the many optional settings that could be specified:
	//$pxf->set_txn_detail('enableAddBillCard', 0);
        $_SESSION['txnref']=substr(uniqid() . rand(1000,9999), 0, 16);
	$pxf->set_txn_detail('txnRef',$_SESSION['txnref'] ); // random 16 digit reference);
        // mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 2', $fld_user_name_val.$fld_pwd_val);	

	// Make the request for a transaction id
	$response = $pxf->get_transaction_id();
	//print_r($response);
         //mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 3', print_r($response));	

	if ( ! $response->GetTransactionIdResult->success)
	{
		// mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 3.5', print_r($response));
		die('There was a problem getting a transaction id from DPS');
	}
	else
	{
		// You should store these values in a database
		// ... they are needed to query the transaction's outcome
		$transaction_id = $response->GetTransactionIdResult->transactionId;
		$session_id = $response->GetTransactionIdResult->sessionId;
		$_SESSION['sessid']=$session_id;
		
		$_SESSION['txnid']=$transaction_id;
		$_SESSION['userid']=$fld_user_name_val;
		$_SESSION['pwd']=$fld_pwd_val;
		$_SESSION['succurl']=$fld_succ_url_val;
		$_SESSION['failurl']=$fld_fail_url_val;
		$_SESSION['paytype']="FUSION";
		//mail('syedaliahmad@gmail.com', 'Getting the Gravity Form Field IDs 4', $_SESSION['fstname'].$_SESSION['lstname']);	

		//must be redirected from gravity form notification
		
	}
	// We've got everything we need to generate 
 }
 
 
 // CUSTOM POST TYPES - TEAM

function codex_team_init() {
	$labels = array(
		'name'               => _x( 'Team', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Team', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Team', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Team', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'diary', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Team Member', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Team Member', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Team Member', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Team Member', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Team Members', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Team Members', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Team Member:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No member found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No member found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'team' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	);

	register_post_type( 'team', $args );
}

add_action( 'init', 'codex_team_init' );

function add_team_meta_boxes() {
	add_meta_box("team_meta", "Team Member Info", "add_team_meta_box", "team", "normal", "high");
}

function add_team_meta_box()
{
	global $post;
	$custom = get_post_custom( $post->ID );
 
	?>
	<style>.width99 {width:99%;}</style>
	<p>
		<label>Position:</label><br />
		<input type="text" name="position" value="<?= @$custom["position"][0] ?>" class="width99" />
	</p>
	<p>
		<label>Education:</label><br />
		<input type="text" name="education" value="<?= @$custom["education"][0] ?>" class="width99" />
	</p>
	<p>
		<label>Short Desription:</label><br />
		<input type="text" name="shortdesc" value="<?= @$custom["shortdesc"][0] ?>" class="width99" />
	</p>
	<?php
}

function save_team_custom_fields(){
  global $post;
 
  if ( $post )
  {
    update_post_meta($post->ID, "position", @$_POST["position"]);
    update_post_meta($post->ID, "education", @$_POST["education"]);
    update_post_meta($post->ID, "shortdesc", @$_POST["shortdesc"]);
  }
}

add_action( 'admin_init', 'add_team_meta_boxes' );
add_action( 'save_post', 'save_team_custom_fields' );

add_action( 'gform_after_submission_1', 'store_last_insert_id_gform', 10, 2);
function store_last_insert_id_gform( $entry, $form ) {
    $_SESSION['allergenics_form_entry'] = $entry;
}
