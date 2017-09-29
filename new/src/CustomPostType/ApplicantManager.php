<?php

namespace Yikes\LevelPlayingField\CustomPostType;

/**
 * Applicant Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
class ApplicantManager extends BaseCustomPostType {

	const SLUG = 'applicants';

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since 0.1.0
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return array(
			'label'               => __( 'Applicant', 'yikes-level-playing-field' ),
			'description'         => __( 'Applicants who have applied for a job through the website form.', 'yikes-level-playing-field' ),
			'labels'              => array(
				'name'                  => _x( 'Applicants', 'Post Type General Name', 'yikes-level-playing-field' ),
				'singular_name'         => _x( 'Applicant', 'Post Type Singular Name', 'yikes-level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Applicant:', 'yikes-level-playing-field' ),
				'all_items'             => __( 'Applicants', 'yikes-level-playing-field' ),
				'add_new_item'          => __( 'Add New Applicant', 'yikes-level-playing-field' ),
				'add_new'               => __( 'Add New Applicant', 'yikes-level-playing-field' ),
				'new_item'              => __( 'New Applicant', 'yikes-level-playing-field' ),
				'edit_item'             => __( 'Edit Applicant', 'yikes-level-playing-field' ),
				'update_item'           => __( 'Update Applicant', 'yikes-level-playing-field' ),
				'view_item'             => __( 'View Applicant', 'yikes-level-playing-field' ),
				'search_items'          => __( 'Search Applicant', 'yikes-level-playing-field' ),
				'not_found'             => __( 'Applicant Not found', 'yikes-level-playing-field' ),
				'not_found_in_trash'    => __( 'Applicant Not found in Trash', 'yikes-level-playing-field' ),
				'featured_image'        => __( 'Applicant Image', 'yikes-level-playing-field' ),
				'set_featured_image'    => __( 'Set applicant image', 'yikes-level-playing-field' ),
				'remove_featured_image' => __( 'Remove applicant image', 'yikes-level-playing-field' ),
				'use_featured_image'    => __( 'Use as applicant image', 'yikes-level-playing-field' ),
				'insert_into_item'      => __( 'Insert into applicant', 'yikes-level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this applicant', 'yikes-level-playing-field' ),
				'items_list'            => __( 'Applicants list', 'yikes-level-playing-field' ),
				'items_list_navigation' => __( 'Applicants list navigation', 'yikes-level-playing-field' ),
				'filter_items_list'     => __( 'Filter applicants list', 'yikes-level-playing-field' ),
			),
			'supports'            => array(),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=jobs',
			'rewrite'             => array( 'slug' => 'applicant' ),
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
	}
}
