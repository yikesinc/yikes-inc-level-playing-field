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
use Yikes\LevelPlayingField\Model\JobMeta;
use Yikes\LevelPlayingField\Model\JobRepository;
use WP_REST_Server;

/**
 * Job Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
class JobManager extends BaseCustomPostType {

	const SLUG          = 'jobs';
	const SINGULAR_SLUG = 'job';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();

		// Modify the default title for the block editor.
		add_filter( 'enter_title_here', function( $title, $post ) {
			if ( $this->get_slug() !== $post->post_type ) {
				return $title;
			}

			return __( 'Add Job Title', 'yikes-level-playing-field' );
		}, 10, 2 );

		add_action( 'rest_api_init', function( WP_REST_Server $server ) {
			$callback = function( $object, $field_name ) {
				static $repo = null;
				if ( null === $repo ) {
					$repo = new JobRepository();
				}

				return $repo->find( $object['id'] )->{"get_{$field_name}"}();
			};

			foreach ( JobMeta::REST_FIELDS as $field ) {
				register_rest_field(
					$this->get_slug(),
					$field,
					[
						'get_callback'    => $callback,
						'update_callback' => null,
						'schema'          => null,
					]
				);
			}
		});
	}

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return [
			'label'               => __( 'Job', 'yikes-level-playing-field' ),
			'description'         => __( 'Job listings.', 'yikes-level-playing-field' ),
			'labels'              => [
				'name'                  => _x( 'Jobs', 'Post Type General Name', 'yikes-level-playing-field' ),
				'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'yikes-level-playing-field' ),
				'menu_name'             => __( 'Level Playing Field', 'yikes-level-playing-field' ),
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
			],
			'supports'            => [ 'title', 'editor' ],
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
			'map_meta_cap'        => true,
			'capabilities'        => [
				'edit_post'              => Capabilities::EDIT_JOB,
				'edit_posts'             => Capabilities::EDIT_JOBS,
				'edit_others_posts'      => Capabilities::EDIT_OTHERS_JOBS,
				'publish_posts'          => Capabilities::PUBLISH_JOBS,
				'read_post'              => Capabilities::READ_JOB,
				'read_private_posts'     => Capabilities::READ_PRIVATE_JOBS,
				'delete_post'            => Capabilities::DELETE_JOB,
				'delete_posts'           => Capabilities::DELETE_JOBS,
				'delete_private_posts'   => Capabilities::DELETE_PRIVATE_JOBS,
				'delete_published_posts' => Capabilities::DELETE_PUBLISHED_JOBS,
				'delete_others_posts'    => Capabilities::DELETE_OTHERS_JOBS,
				'edit_private_posts'     => Capabilities::EDIT_PRIVATE_JOBS,
				'edit_published_posts'   => Capabilities::EDIT_PUBLISHED_JOBS,
				'create_posts'           => Capabilities::CREATE_JOBS,
			],
			'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d=M16,13.14a4.76,4.76,0,0,0-1.63-3.66A11.24,11.24,0,0,1,17.28,11a13,13,0,0,1,2.16,2l.19.22-.19.22a13,13,0,0,1-2.16,1.95A10.8,10.8,0,0,1,14.1,17,4.72,4.72,0,0,0,16,13.14ZM6.73,15.37a14.12,14.12,0,0,1-2.16-1.95l-.18-.22L4.57,13a14.12,14.12,0,0,1,2.16-2A11.37,11.37,0,0,1,9.54,9.52,4.8,4.8,0,0,0,8,13.14,4.72,4.72,0,0,0,9.78,17a11.17,11.17,0,0,1-3-1.59Zm2.78-2.23A2.53,2.53,0,0,1,12,10.55a2.53,2.53,0,0,1,2.46,2.59A2.53,2.53,0,0,1,12,15.74a2.53,2.53,0,0,1-2.46-2.6Zm3.59.77v-.52H10.91v.52Zm0-1V12.4H10.91v.52Zm6.48-1c-2.51-2.38-5.15-3.54-7.87-3.45a11.56,11.56,0,0,0-7.28,3.44.25.25,0,0,1-.37-.33A11.84,11.84,0,0,1,11.69,8c2.86-.1,5.62,1.1,8.23,3.59a.24.24,0,0,1-.17.42.23.23,0,0,1-.17-.07Z"/></svg>' ),
			'rewrite'             => [
				'slug' => _x( 'lpf-jobs', "The CPT's rewrite slug. Translatable as per WP's documentation.", 'yikes-level-playing-field' ),
			],
			'show_in_rest'        => true,
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
		];
	}
}
