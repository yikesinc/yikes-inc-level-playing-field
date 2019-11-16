<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\RequiredPages;

use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Registerable;
use Yikes\LevelPlayingField\Exception\MustExtend;

/**
 * Class BaseRequiredPage
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseRequiredPage implements Registerable, Service {

	const PAGE_SLUG = '_default_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// Create our required pages.
		add_action( 'admin_init', [ $this, 'register_pages' ] );

		// Add a post display state for our messaging page.
		add_filter( 'display_post_states', [ $this, 'required_pages_post_states' ], 10, 2 );

		// Keep our application and messaging page from being moved to trash.
		add_filter( 'pre_trash_post', [ $this, 'prevent_trash_required_pages' ], 10, 2 );

		// Show a notice on attempted trash of a required page.
		add_action( 'admin_notices', [ $this, 'trash_page_failure_notice' ], 20, 2 );

		// Delete our corresponding option if a required page is deleted.
		add_action( 'delete_post', [ $this, 'delete_option_on_page_delete' ], 10, 1 );
	}

	/**
	 * Create the plugin's required pages.
	 *
	 * @since 1.0.0
	 * @throws MustExtend When the default type has not been extended.
	 */
	public function register_pages() {
		if ( self::PAGE_SLUG === static::PAGE_SLUG ) {
			throw MustExtend::default_type( self::PAGE_SLUG );
		}

		if ( $this->get_page_id( static::PAGE_SLUG ) ) {
			return;
		}

		$post_id = wp_insert_post( $this->get_post_array() );

		if ( is_wp_error( $post_id ) || 0 === $post_id ) {
			add_action( 'admin_notices', [ $this, 'create_page_failure_notice' ], 1 );
		} else {

			// Associate the page slug and post ID.
			update_option( static::PAGE_SLUG, $post_id );

			// Show a notice on successful page creation.
			add_action( 'admin_notices', [ $this, 'create_page_success_notice' ], 1 );
		}
	}

	/**
	 * Get the array of post attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return array $post_array The array of post attributes.
	 */
	abstract protected function get_post_array();

	/**
	 * Get the post state string.
	 *
	 * Note: this variable cannot be used as a class constant because it requires the string be run through a translation function.
	 *
	 * @since 1.0.0
	 *
	 * @return string $post_state The description for this post in the list table.
	 */
	abstract protected function get_post_state();

	/**
	 * Add the post state string to the post states array.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $post_states The current post states for this page.
	 * @param  object $post        The post object.
	 * @return array  $post_states The modified post states for this page.
	 */
	public function required_pages_post_states( $post_states, $post ) {

		if ( $this->get_page_id( static::PAGE_SLUG ) === $post->ID && $this->get_post_state() ) {
			$post_states[] = $this->get_post_state();
		}

		return $post_states;
	}

	/**
	 * Set flag to prevent required pages from being trashed.
	 *
	 * @since 1.0.0
	 *
	 * @param bool     $trash Whether to go forward with trashing.
	 * @param /WP_Post $post  Post object.
	 *
	 * @return bool   $trash  Whether to go forward with trashing.
	 */
	public function prevent_trash_required_pages( $trash, $post ) {
		if ( $this->get_page_id( static::PAGE_SLUG ) === $post->ID ) {
			return true;
		}
		return $trash;
	}

	/**
	 * Show a notice if we failed to create a page.
	 *
	 * @since 1.0.0
	 */
	public function trash_page_failure_notice() {

		if ( ! isset( $_REQUEST['ids'] ) ) {
			return;
		}

		$post_ids = explode( ',', $_REQUEST['ids'] );
		if ( ! in_array( (string) $this->get_page_id( static::PAGE_SLUG ), $post_ids, true ) ) {
			return;
		}

		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the post titles for our required pages. */
				esc_html__( 'The following page is required for the Level Playing Field plugin and cannot be moved to Trash: %1$s.', 'level-playing-field' ),
				esc_attr( static::POST_TITLE )
			)
		);
	}

	/**
	 * Delete the plugin's option if the corresponding page is deleted.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id The post ID.
	 */
	public function delete_option_on_page_delete( $post_id ) {
		if ( $this->get_page_id( static::PAGE_SLUG ) === (int) $post_id ) {
			delete_option( static::PAGE_SLUG );
		}
	}

	/**
	 * Get a required page's ID from its slug.
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_slug The page slug we use to designate our required pages.
	 *
	 * @return int $post_id The ID of the required page.
	 */
	public function get_page_id( $page_slug ) {
		$post_id = get_option( filter_var( $page_slug, FILTER_SANITIZE_STRING ), 0 );
		return empty( $post_id ) ? 0 : (int) $post_id;
	}

	/**
	 * Show a notice if we failed to create a page.
	 *
	 * @since 1.0.0
	 */
	public function create_page_failure_notice() {
		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the post titles for our required pages. */
				esc_html__( 'There was an error creating one of the plugin\'s required pages: %1$s.', 'level-playing-field' ),
				esc_attr( static::POST_TITLE )
			)
		);
	}

	/**
	 * Show a notice if we successfully created a page.
	 *
	 * @since 1.0.0
	 */
	public function create_page_success_notice() {
		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the post titles for our required pages. */
				esc_html__( 'The following required page was successfully created: %1$s.', 'level-playing-field' ),
				esc_attr( static::POST_TITLE )
			)
		);
	}
}
