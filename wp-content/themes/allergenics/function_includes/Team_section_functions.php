<?php
//Team stuff

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
/**
function add_team_meta_box() {
	global $post;
	$custom = get_post_custom( $post->ID );
 
	
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
}
**/
function save_team_custom_fields(){
  global $post;
 
  if ( $post )  {
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
?>