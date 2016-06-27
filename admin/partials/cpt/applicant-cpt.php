<?php
// Register Custom Post Type for applicants
function generate_applicant_cpt() {

	$labels = array(
		'name'                  => _x( 'Applicants', 'Post Type General Name', 'yikes-inc-level-playing-field' ),
		'singular_name'         => _x( 'Applicant', 'Post Type Singular Name', 'yikes-inc-level-playing-field' ),
		'parent_item_colon'     => __( 'Parent Applicant:', 'yikes-inc-level-playing-field' ),
		'all_items'             => __( 'All Applicants', 'yikes-inc-level-playing-field' ),
		'add_new_item'          => __( 'Add New Applicant', 'yikes-inc-level-playing-field' ),
		'add_new'               => __( 'Add New Applicant', 'yikes-inc-level-playing-field' ),
		'new_item'              => __( 'New Applicant', 'yikes-inc-level-playing-field' ),
		'edit_item'             => __( 'Edit v', 'yikes-inc-level-playing-field' ),
		'update_item'           => __( 'Update Applicant', 'yikes-inc-level-playing-field' ),
		'view_item'             => __( 'View Applicant', 'yikes-inc-level-playing-field' ),
		'search_items'          => __( 'Search Applicant', 'yikes-inc-level-playing-field' ),
		'not_found'             => __( 'Applicant Not found', 'yikes-inc-level-playing-field' ),
		'not_found_in_trash'    => __( 'Applicant Not found in Trash', 'yikes-inc-level-playing-field' ),
		'featured_image'        => __( 'Applicant Image', 'yikes-inc-level-playing-field' ),
		'set_featured_image'    => __( 'Set applicant image', 'yikes-inc-level-playing-field' ),
		'remove_featured_image' => __( 'Remove applicant image', 'yikes-inc-level-playing-field' ),
		'use_featured_image'    => __( 'Use as applicant image', 'yikes-inc-level-playing-field' ),
		'insert_into_item'      => __( 'Insert into applicant', 'yikes-inc-level-playing-field' ),
		'uploaded_to_this_item' => __( 'Uploaded to this applicant', 'yikes-inc-level-playing-field' ),
		'items_list'            => __( 'Applicants list', 'yikes-inc-level-playing-field' ),
		'items_list_navigation' => __( 'Applicants list navigation', 'yikes-inc-level-playing-field' ),
		'filter_items_list'     => __( 'Filter applicants list', 'yikes-inc-level-playing-field' ),
	);
	$args = array(
		'label'                 => __( 'Applicant', 'yikes-inc-level-playing-field' ),
		'description'           => __( 'Applicants who have applied for a job through the website form.', 'yikes-inc-level-playing-field' ),
		'labels'                => $labels,
		'supports'              => array(),
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
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'applicants', $args );
}
add_action( 'init', 'generate_applicant_cpt', 0 );
