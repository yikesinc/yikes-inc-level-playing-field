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
use Yikes\LevelPlayingField\Model\JobDescriptionPlaceholder;
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

	use JobDescriptionPlaceholder;

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		parent::register();

		// Modify the default title for the block editor.
		add_filter( 'enter_title_here', function( $title, $post ) {
			if ( $this->get_slug() !== $post->post_type ) {
				return $title;
			}

			return __( 'Add Job Title', 'level-playing-field' );
		}, 10, 2 );

		// Modify the default paragraph for the block editor.
		add_filter( 'write_your_story', function( $title, $post ) {
			if ( $this->get_slug() !== $post->post_type ) {
				return $title;
			}

			return $this->get_job_description_placeholder();
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
	 * @since 1.0.0
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments() {
		return [
			'label'               => __( 'Job', 'level-playing-field' ),
			'description'         => __( 'Job listings.', 'level-playing-field' ),
			'labels'              => [
				'name'                  => _x( 'Jobs', 'Post Type General Name', 'level-playing-field' ),
				'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'level-playing-field' ),
				'menu_name'             => __( 'Level Playing Field', 'level-playing-field' ),
				'name_admin_bar'        => __( 'Jobs', 'level-playing-field' ),
				'archives'              => __( 'Job Archives', 'level-playing-field' ),
				'parent_item_colon'     => __( 'Parent Job:', 'level-playing-field' ),
				'all_items'             => __( 'All Jobs', 'level-playing-field' ),
				'add_new_item'          => __( 'Add New Job', 'level-playing-field' ),
				'add_new'               => __( 'Add New Job', 'level-playing-field' ),
				'new_item'              => __( 'New Job', 'level-playing-field' ),
				'edit_item'             => __( 'Edit Job', 'level-playing-field' ),
				'update_item'           => __( 'Update Job', 'level-playing-field' ),
				'view_item'             => __( 'View Job', 'level-playing-field' ),
				'search_items'          => __( 'Search Job', 'level-playing-field' ),
				'not_found'             => __( 'Job Not found', 'level-playing-field' ),
				'not_found_in_trash'    => __( 'Job Not found in Trash', 'level-playing-field' ),
				'featured_image'        => __( 'Job Image', 'level-playing-field' ),
				'set_featured_image'    => __( 'Set Job image', 'level-playing-field' ),
				'remove_featured_image' => __( 'Remove Job image', 'level-playing-field' ),
				'use_featured_image'    => __( 'Use as Job image', 'level-playing-field' ),
				'insert_into_item'      => __( 'Insert into Job', 'level-playing-field' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Job', 'level-playing-field' ),
				'items_list'            => __( 'Jobs list', 'level-playing-field' ),
				'items_list_navigation' => __( 'Jobs list navigation', 'level-playing-field' ),
				'filter_items_list'     => __( 'Filter Jobs list', 'level-playing-field' ),
			],
			'supports'            => [ 'title', 'editor', 'excerpt' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
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
			'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.63 13.23"><path d="M3.78,14.35a10.46,10.46,0,0,1,6.51-4.63A5.1,5.1,0,0,0,7.78,15.8a4.54,4.54,0,0,0,3,3.2,10.32,10.32,0,0,1-7-4.65Zm9.36,4.55a5.08,5.08,0,0,0,2.68-6.17,4.67,4.67,0,0,0-2.47-3,10.83,10.83,0,0,1,6.5,4.62,10.57,10.57,0,0,1-7,4.64l.28-.09ZM9.09,14.56a2.66,2.66,0,1,1,3,2.34,2.68,2.68,0,0,1-3-2.34Zm4,.76V14.5H10.36v.82Zm0-1.33v-.83H10.36V14ZM3.23,10.77a.83.83,0,0,1,0-1.17c5.24-5,11.64-5.15,17.12,0a.83.83,0,0,1,0,1.17.82.82,0,0,1-1.17,0C14.41,6.26,9,6.36,4.39,10.79a.81.81,0,0,1-1.16,0Z" transform="translate(-3 -5.77)" style="fill-rule:evenodd"/></svg>' ),
			'rewrite'             => [
				'slug' => _x( 'lpf-jobs', "The CPT's rewrite slug. Translatable as per WP's documentation.", 'level-playing-field' ),
			],
			'show_in_rest'        => true,
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
		$permalink = get_permalink( $post );

		return [
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: permalink URL */
			1  => sprintf( __( 'Job updated. <a target="_blank" href="%s">View Job</a>', 'level-playing-field' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'level-playing-field' ),
			3  => __( 'Custom field deleted.', 'level-playing-field' ),
			4  => __( 'Job updated.', 'level-playing-field' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Job restored to revision from %s', 'level-playing-field' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			/* translators: %s: permalink URL */
			6  => sprintf( __( 'Job published. <a href="%s">View Job</a>', 'level-playing-field' ), esc_url( $permalink ) ),
			7  => __( 'Job saved.', 'level-playing-field' ),
			/* translators: %s: preview URL */
			8  => sprintf( __( 'Job submitted. <a target="_blank" href="%s">Preview Job</a>', 'level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			9  => sprintf(
				/* translators: %1$s: translated date. %2$s: permalink URL */
				__( 'Job scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Job</a>', 'level-playing-field' ),
				/* translators: Publish box date format, see http://php.net/date */
				date_i18n( __( 'M j, Y @ G:i', 'level-playing-field' ), strtotime( $post->post_date ) ),
				esc_url( $permalink )
			),
			/* translators: %s: preview URL */
			10 => sprintf( __( 'Job draft updated. <a target="_blank" href="%s">Preview Job</a>', 'level-playing-field' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		];
	}
}
