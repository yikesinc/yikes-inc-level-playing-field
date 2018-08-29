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
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return [
			'label'               => __( 'Applicant', 'yikes-level-playing-field' ),
			'description'         => __( 'Applicants who have applied for a job through the website form.', 'yikes-level-playing-field' ),
			'labels'              => [
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
				'set_featured_image'    => __( 'Set Applicant image', 'yikes-level-playing-field' ),
				'remove_featured_image' => __( 'Remove Applicant image', 'yikes-level-playing-field' ),
				'use_featured_image'    => __( 'Use as Applicant image', 'yikes-level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Applicant', 'yikes-level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Applicant', 'yikes-level-playing-field' ),
				'items_list'            => __( 'Applicants list', 'yikes-level-playing-field' ),
				'items_list_navigation' => __( 'Applicants list navigation', 'yikes-level-playing-field' ),
				'filter_items_list'     => __( 'Filter Applicants list', 'yikes-level-playing-field' ),
			],
			'supports'            => [],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
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
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_messages() {
		global $post;
		$permalink = get_permalink( $post );

		return [
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: permalink URL */
			1  => sprintf( __( 'Applicant updated. <a target="_blank" href="%s">View Applicant</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'yikes-level-playing-field' ),
			3  => __( 'Custom field deleted.', 'yikes-level-playing-field' ),
			4  => __( 'Applicant updated.', 'yikes-level-playing-field' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Applicant restored to revision from %s', 'yikes-level-playing-field' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: permalink URL */
			6  => sprintf( __( 'Applicant published. <a href="%s">View Applicant</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			7  => __( 'Applicant saved.', 'yikes-level-playing-field' ),
			/* translators: %s: preview URL */
			8  => sprintf( __( 'Applicant submitted. <a target="_blank" href="%s">Preview Applicant</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: %1$s: translated date. %2$s: permalink URL */
				__( 'Applicant scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Applicant</a>', 'yikes-level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'yikes-level-playing-field' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %s: preview URL */
			10 => sprintf( __( 'Applicant draft updated. <a target="_blank" href="%s">Preview Applicant</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		];
	}
}
