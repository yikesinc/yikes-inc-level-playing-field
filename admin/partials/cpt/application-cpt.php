<?php
// Register Custom Post Type for applicantions
function generate_application_cpt() {

	$labels = array(
		'name'                  => _x( 'Applications', 'Post Type General Name', 'yikes-inc-level-playing-field' ),
		'singular_name'         => _x( 'Application', 'Post Type Singular Name', 'yikes-inc-level-playing-field' ),
		'parent_item_colon'     => __( 'Parent Application:', 'yikes-inc-level-playing-field' ),
		'all_items'             => __( 'Job Applications', 'yikes-inc-level-playing-field' ),
		'add_new_item'          => __( 'Add New Application', 'yikes-inc-level-playing-field' ),
		'add_new'               => __( 'Add New Application', 'yikes-inc-level-playing-field' ),
		'new_item'              => __( 'New Application', 'yikes-inc-level-playing-field' ),
		'edit_item'             => __( 'Edit Application', 'yikes-inc-level-playing-field' ),
		'update_item'           => __( 'Update Application', 'yikes-inc-level-playing-field' ),
		'view_item'             => __( 'View Application', 'yikes-inc-level-playing-field' ),
		'search_items'          => __( 'Search Application', 'yikes-inc-level-playing-field' ),
		'not_found'             => __( 'Application Not found', 'yikes-inc-level-playing-field' ),
		'not_found_in_trash'    => __( 'Application Not found in Trash', 'yikes-inc-level-playing-field' ),
		'featured_image'        => __( 'Application Image', 'yikes-inc-level-playing-field' ),
		'set_featured_image'    => __( 'Set applications image', 'yikes-inc-level-playing-field' ),
		'remove_featured_image' => __( 'Remove applications image', 'yikes-inc-level-playing-field' ),
		'use_featured_image'    => __( 'Use as applications image', 'yikes-inc-level-playing-field' ),
		'insert_into_item'      => __( 'Insert into application', 'yikes-inc-level-playing-field' ),
		'uploaded_to_this_item' => __( 'Uploaded to this application', 'yikes-inc-level-playing-field' ),
		'items_list'            => __( 'Applications list', 'yikes-inc-level-playing-field' ),
		'items_list_navigation' => __( 'Applications list navigation', 'yikes-inc-level-playing-field' ),
		'filter_items_list'     => __( 'Filter applications list', 'yikes-inc-level-playing-field' ),
	);
	$args = array(
		'label'                 => __( 'Job Applications', 'yikes-inc-level-playing-field' ),
		'description'           => __( 'Job applications that are associated with the level playing field jobs.', 'yikes-inc-level-playing-field' ),
		'labels'                => $labels,
		'supports'              => array(),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=jobs',
		'rewrite'								=> array( 'slug' => 'application' ), // set the slug to applicant, instead of applicants
		'menu_position'         => 10,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'applications', $args );
}
add_action( 'init', 'generate_application_cpt', 0 );

/**
 * Alter the application 'Publish' button text
 * @param  string $translation The translation text.
 * @param  string $text        The text to use as comparison.
 * @return string              [description]
 */
function change_publish_button( $translation, $text ) {
	global $post;
	if ( ! isset( $post->post_type ) || 'applications' !== $post->post_type ) :
		return $translation;
	endif;
	// If the te
	if ( 'Publish' !== $text ) :
		return $translation;
	endif;
	// Return the original text
	return __( 'Save Application', 'yikes-inc-level-playing-field' );
}
add_filter( 'gettext', 'change_publish_button', 10, 2 );
