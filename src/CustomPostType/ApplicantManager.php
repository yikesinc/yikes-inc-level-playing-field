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
 * Applicant Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
final class ApplicantManager extends BaseCustomPostType {

	const SLUG          = 'applicants';
	const SINGULAR_SLUG = 'applicant';

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return [
			'label'               => __( 'Applicant', 'level-playing-field' ),
			'description'         => __( 'Applicants who have applied for a job through the website form.', 'level-playing-field' ),
			'labels'              => [
				'name'                  => _x( 'Applicants', 'Post Type General Name', 'level-playing-field' ),
				'singular_name'         => _x( 'Applicant', 'Post Type Singular Name', 'level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Applicant:', 'level-playing-field' ),
				'all_items'             => __( 'Applicants', 'level-playing-field' ),
				'add_new_item'          => __( 'Add New Applicant', 'level-playing-field' ),
				'add_new'               => __( 'Add New Applicant', 'level-playing-field' ),
				'new_item'              => __( 'New Applicant', 'level-playing-field' ),
				'edit_item'             => __( 'View Applicant', 'level-playing-field' ),
				'update_item'           => __( 'Update Applicant', 'level-playing-field' ),
				'view_item'             => __( 'View Applicant', 'level-playing-field' ),
				'search_items'          => __( 'Search Applicant', 'level-playing-field' ),
				'not_found'             => __( 'Applicant Not found', 'level-playing-field' ),
				'not_found_in_trash'    => __( 'Applicant Not found in Trash', 'level-playing-field' ),
				'featured_image'        => __( 'Applicant Image', 'level-playing-field' ),
				'set_featured_image'    => __( 'Set Applicant image', 'level-playing-field' ),
				'remove_featured_image' => __( 'Remove Applicant image', 'level-playing-field' ),
				'use_featured_image'    => __( 'Use as Applicant image', 'level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Applicant', 'level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Applicant', 'level-playing-field' ),
				'items_list'            => __( 'Applicants list', 'level-playing-field' ),
				'items_list_navigation' => __( 'Applicants list navigation', 'level-playing-field' ),
				'filter_items_list'     => __( 'Filter Applicants list', 'level-playing-field' ),
			],
			'supports'            => false,
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=' . JobManager::SLUG,
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
				'edit_post'              => Capabilities::EDIT_APPLICANT,
				'edit_posts'             => Capabilities::EDIT_APPLICANTS,
				'edit_others_posts'      => Capabilities::EDIT_OTHERS_APPLICANTS,
				'publish_posts'          => Capabilities::PUBLISH_APPLICANTS,
				'read_post'              => Capabilities::READ_APPLICANT,
				'read_private_posts'     => Capabilities::READ_PRIVATE_APPLICANTS,
				'delete_post'            => Capabilities::DELETE_APPLICANT,
				'delete_posts'           => Capabilities::DELETE_APPLICANTS,
				'delete_private_posts'   => Capabilities::DELETE_PRIVATE_APPLICANTS,
				'delete_published_posts' => Capabilities::DELETE_PUBLISHED_APPLICANTS,
				'delete_others_posts'    => Capabilities::DELETE_OTHERS_APPLICANTS,
				'edit_private_posts'     => Capabilities::EDIT_PRIVATE_APPLICANTS,
				'edit_published_posts'   => Capabilities::EDIT_PUBLISHED_APPLICANTS,
				'create_posts'           => Capabilities::CREATE_APPLICANTS,
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
			1  => __( 'Applicant updated.', 'level-playing-field' ),
			2  => __( 'Custom field updated.', 'level-playing-field' ),
			3  => __( 'Custom field deleted.', 'level-playing-field' ),
			4  => __( 'Applicant updated.', 'level-playing-field' ),
			5  => isset( $_GET['revision'] )
				? sprintf(
					/* translators: %s: date and time of the revision */
					__( 'Applicant restored to revision from %s', 'level-playing-field' ),
					wp_post_revision_title( (int) $_GET['revision'], false )
				)
				: false,
			6  => __( 'Applicant published.', 'level-playing-field' ),
			7  => __( 'Applicant saved.', 'level-playing-field' ),
			8  => __( 'Applicant submitted.', 'level-playing-field' ),
			9  => sprintf(
				/* translators: %1$s: translated date. */
				__( 'Applicant scheduled for: <strong>%1$s</strong>.', 'level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'level-playing-field' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Applicant draft updated.', 'level-playing-field' ),
		];
	}
}
