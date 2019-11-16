<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Application Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
final class ApplicationManager extends BaseCustomPostType {

	const SLUG          = 'applications';
	const SINGULAR_SLUG = 'application';

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return [
			'label'               => __( 'Applications', 'level-playing-field' ),
			'description'         => __( 'Job Applications that are associated with the level playing field jobs.', 'level-playing-field' ),
			'labels'              => [
				'name'                  => _x( 'Application Forms', 'Post Type General Name', 'level-playing-field' ),
				'singular_name'         => _x( 'Application Form', 'Post Type Singular Name', 'level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Application Form:', 'level-playing-field' ),
				'all_items'             => __( 'Application Forms', 'level-playing-field' ),
				'add_new_item'          => __( 'Add New Application Form', 'level-playing-field' ),
				'add_new'               => __( 'Add New Application Form', 'level-playing-field' ),
				'new_item'              => __( 'New Application Form', 'level-playing-field' ),
				'edit_item'             => __( 'Edit Application Form', 'level-playing-field' ),
				'update_item'           => __( 'Update Application Form', 'level-playing-field' ),
				'view_item'             => __( 'View Application Form', 'level-playing-field' ),
				'search_items'          => __( 'Search Application Forms', 'level-playing-field' ),
				'not_found'             => __( 'Application Form Not found', 'level-playing-field' ),
				'not_found_in_trash'    => __( 'Application Form Not found in Trash', 'level-playing-field' ),
				'featured_image'        => __( 'Application Form Image', 'level-playing-field' ),
				'set_featured_image'    => __( 'Set Application Form image', 'level-playing-field' ),
				'remove_featured_image' => __( 'Remove Application Form image', 'level-playing-field' ),
				'use_featured_image'    => __( 'Use as Application Form image', 'level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Application Form', 'level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Application Form', 'level-playing-field' ),
				'items_list'            => __( 'Application Forms list', 'level-playing-field' ),
				'items_list_navigation' => __( 'Application Forms list navigation', 'level-playing-field' ),
				'filter_items_list'     => __( 'Filter Application Forms list', 'level-playing-field' ),
			],
			'supports'            => [ 'title' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=jobs',
			'rewrite'             => [
				'slug' => self::SINGULAR_SLUG,
			],
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'map_meta_cap'        => true,
			'capabilities'        => [
				'edit_post'              => Capabilities::EDIT_APPLICATION,
				'edit_posts'             => Capabilities::EDIT_APPLICATIONS,
				'edit_others_posts'      => Capabilities::EDIT_OTHERS_APPLICATIONS,
				'publish_posts'          => Capabilities::PUBLISH_APPLICATIONS,
				'read_post'              => Capabilities::READ_APPLICATION,
				'read_private_posts'     => Capabilities::READ_PRIVATE_APPLICATIONS,
				'delete_post'            => Capabilities::DELETE_APPLICATION,
				'delete_posts'           => Capabilities::DELETE_APPLICATIONS,
				'delete_private_posts'   => Capabilities::DELETE_PRIVATE_APPLICATIONS,
				'delete_published_posts' => Capabilities::DELETE_PUBLISHED_APPLICATIONS,
				'delete_others_posts'    => Capabilities::DELETE_OTHERS_APPLICATIONS,
				'edit_private_posts'     => Capabilities::EDIT_PRIVATE_APPLICATIONS,
				'edit_published_posts'   => Capabilities::EDIT_PUBLISHED_APPLICATIONS,
				'create_posts'           => Capabilities::CREATE_APPLICATIONS,
			],
		];
	}

	/**
	 * Get the array of messages to use when updating.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_messages() {
		global $post;

		return [
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Application updated.', 'level-playing-field' ),
			2  => __( 'Custom field updated.', 'level-playing-field' ),
			3  => __( 'Custom field deleted.', 'level-playing-field' ),
			4  => __( 'Application updated.', 'level-playing-field' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Application restored to revision from %s', 'level-playing-field' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Application published.', 'level-playing-field' ),
			7  => __( 'Application saved.', 'level-playing-field' ),
			8  => __( 'Application submitted.', 'level-playing-field' ),
			9  => sprintf(
				/* translators: %1$s: translated date. */
				__( 'Application scheduled for: <strong>%1$s</strong>.', 'level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'level-playing-field' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Application draft updated.', 'level-playing-field' ),
		];
	}
}
