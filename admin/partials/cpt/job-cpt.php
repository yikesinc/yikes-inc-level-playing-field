<?php
// Register Custom Post Type
function generate_job_cpt() {

	$labels = array(
		'name'                  => _x( 'Jobs', 'Post Type General Name', 'yikes-inc-level-playing-field' ),
		'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'yikes-inc-level-playing-field' ),
		'menu_name'             => __( 'Level Playing Field', 'yikes-inc-level-playing-field' ),
		'name_admin_bar'        => __( 'Jobs', 'yikes-inc-level-playing-field' ),
		'archives'              => __( 'Job Archives', 'yikes-inc-level-playing-field' ),
		'parent_item_colon'     => __( 'Parent Job:', 'yikes-inc-level-playing-field' ),
		'all_items'             => __( 'All Jobs', 'yikes-inc-level-playing-field' ),
		'add_new_item'          => __( 'Add New Job', 'yikes-inc-level-playing-field' ),
		'add_new'               => __( 'Add New Job', 'yikes-inc-level-playing-field' ),
		'new_item'              => __( 'New Job', 'yikes-inc-level-playing-field' ),
		'edit_item'             => __( 'Edit Job', 'yikes-inc-level-playing-field' ),
		'update_item'           => __( 'Update Job', 'yikes-inc-level-playing-field' ),
		'view_item'             => __( 'View Job', 'yikes-inc-level-playing-field' ),
		'search_items'          => __( 'Search Job', 'yikes-inc-level-playing-field' ),
		'not_found'             => __( 'Job Not found', 'yikes-inc-level-playing-field' ),
		'not_found_in_trash'    => __( 'Job Not found in Trash', 'yikes-inc-level-playing-field' ),
		'featured_image'        => __( 'Job Image', 'yikes-inc-level-playing-field' ),
		'set_featured_image'    => __( 'Set job image', 'yikes-inc-level-playing-field' ),
		'remove_featured_image' => __( 'Remove job image', 'yikes-inc-level-playing-field' ),
		'use_featured_image'    => __( 'Use as job image', 'yikes-inc-level-playing-field' ),
		'insert_into_item'      => __( 'Insert into job', 'yikes-inc-level-playing-field' ),
		'uploaded_to_this_item' => __( 'Uploaded to this job', 'yikes-inc-level-playing-field' ),
		'items_list'            => __( 'Jobs list', 'yikes-inc-level-playing-field' ),
		'items_list_navigation' => __( 'Jobs list navigation', 'yikes-inc-level-playing-field' ),
		'filter_items_list'     => __( 'Filter jobs list', 'yikes-inc-level-playing-field' ),
	);
	$args = array(
		'label'                 => __( 'Job', 'yikes-inc-level-playing-field' ),
		'description'           => __( 'Job listings.', 'yikes-inc-level-playing-field' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', ),
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'jobs', $args );

}
add_action( 'init', 'generate_job_cpt', 0 );


/**
 * Register the custom taxonomies for Jobs cpt
 */
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_book_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_book_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Job Categories', 'yikes-inc-level-playing-field' ),
		'singular_name'     => _x( 'Job Category', 'yikes-inc-level-playing-field' ),
		'search_items'      => __( 'Search Job Categories', 'yikes-inc-level-playing-field' ),
		'all_items'         => __( 'All Job Categories', 'yikes-inc-level-playing-field' ),
		'parent_item'       => __( 'Parent Job Category', 'yikes-inc-level-playing-field' ),
		'parent_item_colon' => __( 'Parent Job Category:', 'yikes-inc-level-playing-field' ),
		'edit_item'         => __( 'Edit Job Category', 'yikes-inc-level-playing-field' ),
		'update_item'       => __( 'Update Job Category', 'yikes-inc-level-playing-field' ),
		'add_new_item'      => __( 'Add New Job Category', 'yikes-inc-level-playing-field' ),
		'new_item_name'     => __( 'New Job Category', 'yikes-inc-level-playing-field' ),
		'menu_name'         => __( 'Job Categories', 'yikes-inc-level-playing-field' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'job-categories' ),
	);

	register_taxonomy( 'job-categories', 'jobs', $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Job Tags', 'yikes-inc-level-playing-field' ),
		'singular_name'              => _x( 'Job Tags', 'yikes-inc-level-playing-field' ),
		'search_items'               => __( 'Search Job Tags', 'yikes-inc-level-playing-field' ),
		'popular_items'              => __( 'Popular Job Tags', 'yikes-inc-level-playing-field' ),
		'all_items'                  => __( 'All Job Tags', 'yikes-inc-level-playing-field' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Job Tag', 'yikes-inc-level-playing-field' ),
		'update_item'                => __( 'Update Job Tag', 'yikes-inc-level-playing-field' ),
		'add_new_item'               => __( 'Add New Job Tag', 'yikes-inc-level-playing-field' ),
		'new_item_name'              => __( 'New Job Tag', 'yikes-inc-level-playing-field' ),
		'separate_items_with_commas' => __( 'Separate job tags with commas', 'yikes-inc-level-playing-field' ),
		'add_or_remove_items'        => __( 'Add or remove job tags', 'yikes-inc-level-playing-field' ),
		'choose_from_most_used'      => __( 'Choose from the most used job tags', 'yikes-inc-level-playing-field' ),
		'not_found'                  => __( 'No job tags found.', 'yikes-inc-level-playing-field' ),
		'menu_name'                  => __( 'Job Tags', 'yikes-inc-level-playing-field' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'job-tags', 'yikes-inc-level-playing-field' ),
	);

	register_taxonomy( 'job-tags', 'jobs', $args );
}

/**
 * CPT Title placeholder
 */
function alter_job_cpt_title_placeholder( $title ) {
	$screen = get_current_screen();
	if ( 'jobs' === $screen->post_type ) {
		$title = __( 'Job Title', 'yikes-inc-level-playing-field' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'alter_job_cpt_title_placeholder' );
