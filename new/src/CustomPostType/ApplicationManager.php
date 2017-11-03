<?php

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Application Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
class ApplicationManager extends BaseCustomPostType {

	const SLUG = 'applications';
	const SINGULAR_SLUG = 'application';

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return array(
			'label'               => __( 'Applications', 'yikes-level-playing-field' ),
			'description'         => __( 'Job Applications that are associated with the level playing field jobs.', 'yikes-level-playing-field' ),
			'labels'              => array(
				'name'                  => _x( 'Applications', 'Post Type General Name', 'yikes-level-playing-field' ),
				'singular_name'         => _x( 'Application', 'Post Type Singular Name', 'yikes-level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Application:', 'yikes-level-playing-field' ),
				'all_items'             => __( 'All Applications', 'yikes-level-playing-field' ),
				'add_new_item'          => __( 'Add New Application', 'yikes-level-playing-field' ),
				'add_new'               => __( 'Add New Application', 'yikes-level-playing-field' ),
				'new_item'              => __( 'New Application', 'yikes-level-playing-field' ),
				'edit_item'             => __( 'Edit Application', 'yikes-level-playing-field' ),
				'update_item'           => __( 'Update Application', 'yikes-level-playing-field' ),
				'view_item'             => __( 'View Application', 'yikes-level-playing-field' ),
				'search_items'          => __( 'Search Application', 'yikes-level-playing-field' ),
				'not_found'             => __( 'Application Not found', 'yikes-level-playing-field' ),
				'not_found_in_trash'    => __( 'Application Not found in Trash', 'yikes-level-playing-field' ),
				'featured_image'        => __( 'Application Image', 'yikes-level-playing-field' ),
				'set_featured_image'    => __( 'Set Application image', 'yikes-level-playing-field' ),
				'remove_featured_image' => __( 'Remove Application image', 'yikes-level-playing-field' ),
				'use_featured_image'    => __( 'Use as Application image', 'yikes-level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Application', 'yikes-level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Application', 'yikes-level-playing-field' ),
				'items_list'            => __( 'Applications list', 'yikes-level-playing-field' ),
				'items_list_navigation' => __( 'Applications list navigation', 'yikes-level-playing-field' ),
				'filter_items_list'     => __( 'Filter Applications list', 'yikes-level-playing-field' ),
			),
			'supports'            => array( 'title' ),
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=jobs',
			'rewrite'             => array(
				'slug' => 'application',
			),
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'map_meta_cap'        => true,
			'capabilities'        => array(
				'edit_post'          => Capabilities::EDIT_APPLICATION,
				'edit_posts'         => Capabilities::EDIT_APPLICATIONS,
				'edit_others_posts'  => Capabilities::EDIT_OTHERS_APPLICATIONS,
				'publish_posts'      => Capabilities::PUBLISH_APPLICATIONS,
				'read_post'          => Capabilities::READ_APPLICATION,
				'read_private_posts' => Capabilities::READ_PRIVATE_APPLICATIONS,
				'delete_post'        => Capabilities::DELETE_APPLICATION,
			),
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
			1  => sprintf( __( 'Application updated. <a target="_blank" href="%s">View Application</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'yikes-level-playing-field' ),
			3  => __( 'Custom field deleted.', 'yikes-level-playing-field' ),
			4  => __( 'Application updated.', 'yikes-level-playing-field' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Application restored to revision from %s', 'yikes-level-playing-field' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: permalink URL */
			6  => sprintf( __( 'Application published. <a href="%s">View Application</a>', 'yikes-level-playing-field' ), esc_url( $permalink ) ),
			7  => __( 'Application saved.', 'yikes-level-playing-field' ),
			/* translators: %s: preview URL */
			8  => sprintf( __( 'Application submitted. <a target="_blank" href="%s">Preview Application</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: %1$s: translated date. %2$s: permalink URL */
				__( 'Application scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Application</a>', 'yikes-level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'yikes-level-playing-field' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %s: preview URL */
			10 => sprintf( __( 'Application draft updated. <a target="_blank" href="%s">Preview Application</a>', 'yikes-level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		);
	}
}
