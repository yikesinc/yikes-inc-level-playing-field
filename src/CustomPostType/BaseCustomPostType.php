<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Activateable;
use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseCustomPostType.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 *
 * @property string $slug The CPT slug.
 */
abstract class BaseCustomPostType implements Activateable, Service {

	const SLUG = '_basecpt_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'post_updated_messages', [ $this, 'updated_messages' ] );
	}

	/**
	 * Activate the service.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		$this->register_post_type();
	}

	/**
	 * Register the custom post type.
	 *
	 * @author Jeremy Pry
	 */
	public function register_post_type() {
		register_post_type( $this->get_slug(), $this->get_arguments() );
	}

	/**
	 * Get the slug to use for the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string Custom post type slug.
	 * @throws MustExtend When the default slug has not been extended.
	 */
	protected function get_slug() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}

		return static::SLUG;
	}

	/**
	 * Include our custom messages to use when the CPT is updated.
	 *
	 * @author Jeremy Pry
	 * @since  1.0.0
	 *
	 * @param array $messages Array of existing messages.
	 *
	 * @return array
	 */
	public function updated_messages( $messages ) {
		$messages[ $this->get_slug() ] = $this->get_messages();

		return $messages;
	}

	/**
	 * Getter for class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The property name.
	 *
	 * @return mixed The property if accessible, or null.
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'slug':
				return $this->get_slug();

			default:
				return null;
		}
	}

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of arguments.
	 */
	abstract protected function get_arguments();

	/**
	 * Get the array of messages to use when updating.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 * @return array
	 */
	abstract protected function get_messages();

	/**
	 * Get the URL of the add new page.
	 *
	 * @throws MustExtend When the default slug has not been extended.
	 * @return string The full URL to the add new page for the respective post type.
	 */
	public function get_add_new_url() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}

		return add_query_arg( [ 'post_type' => static::SLUG ], admin_url( 'post-new.php' ) );
	}

	/**
	 * Get the URL of the list table page.
	 *
	 * @throws MustExtend When the default slug has not been extended.
	 * @return string The full URL to the list table page for the respective post type.
	 */
	public function get_list_table_url() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}

		return add_query_arg( [ 'post_type' => static::SLUG ], admin_url( 'edit.php' ) );
	}
}
