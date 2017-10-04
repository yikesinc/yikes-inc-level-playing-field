<?php

namespace Yikes\LevelPlayingField\CustomPostType;

/**
 * Job Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
class JobManager extends BaseCustomPostType {

	const SLUG = 'jobs';

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return array(
			'label'               => __( 'Job', 'yikes-level-playing-field' ),
			'description'         => __( 'Job listings.', 'yikes-level-playing-field' ),
			'labels'              => array(
				'name'                  => _x( 'Jobs', 'Post Type General Name', 'yikes-level-playing-field' ),
				'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'yikes-level-playing-field' ),
				'menu_name'             => __( 'Job Manager', 'yikes-level-playing-field' ),
				'name_admin_bar'        => __( 'Jobs', 'yikes-level-playing-field' ),
				'archives'              => __( 'Job Archives', 'yikes-level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Job:', 'yikes-level-playing-field' ),
				'all_items'             => __( 'All Jobs', 'yikes-level-playing-field' ),
				'add_new_item'          => __( 'Add New Job', 'yikes-level-playing-field' ),
				'add_new'               => __( 'Add New Job', 'yikes-level-playing-field' ),
				'new_item'              => __( 'New Job', 'yikes-level-playing-field' ),
				'edit_item'             => __( 'Edit Job', 'yikes-level-playing-field' ),
				'update_item'           => __( 'Update Job', 'yikes-level-playing-field' ),
				'view_item'             => __( 'View Job', 'yikes-level-playing-field' ),
				'search_items'          => __( 'Search Job', 'yikes-level-playing-field' ),
				'not_found'             => __( 'Job Not found', 'yikes-level-playing-field' ),
				'not_found_in_trash'    => __( 'Job Not found in Trash', 'yikes-level-playing-field' ),
				'featured_image'        => __( 'Job Image', 'yikes-level-playing-field' ),
				'set_featured_image'    => __( 'Set Job image', 'yikes-level-playing-field' ),
				'remove_featured_image' => __( 'Remove Job image', 'yikes-level-playing-field' ),
				'use_featured_image'    => __( 'Use as Job image', 'yikes-level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Job', 'yikes-level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Job', 'yikes-level-playing-field' ),
				'items_list'            => __( 'Jobs list', 'yikes-level-playing-field' ),
				'items_list_navigation' => __( 'Jobs list navigation', 'yikes-level-playing-field' ),
				'filter_items_list'     => __( 'Filter Jobs list', 'yikes-level-playing-field' ),
			),
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-businessman',
		);
	}

	/**
	 * Get the array of messages to use when updating.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_messages() {
		global $post;
		$permalink = get_permalink( $post );

		return array(
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: permalink URL */
			1  => sprintf( __( 'Job updated. <a target="_blank" href="%s">View Job</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'yikes-level-playing-field' ),
			3  => __( 'Custom field deleted.', 'yikes-level-playing-field' ),
			4  => __( 'Job updated.', 'yikes-level-playing-field' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Job restored to revision from %s', 'yikes-level-playing-field' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: permalink URL */
			6  => sprintf( __( 'Job published. <a href="%s">View Job</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			7  => __( 'Job saved.', 'yikes-level-playing-field' ),
			/* translators: %s: preview URL */
			8  => sprintf( __( 'Job submitted. <a target="_blank" href="%s">Preview Job</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: %1$s: translated date. %2$s: permalink URL */
				__( 'Job scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Job</a>', 'yikes-level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'yikes-level-playing-field' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %s: preview URL */
			10 => sprintf( __( 'Job draft updated. <a target="_blank" href="%s">Preview Job</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);
	}

}
