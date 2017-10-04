<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Exception\MustExtendSlug;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseCustomPostType.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseCustomPostType implements Service {

	const SLUG = '_basecpt_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
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
	 * @since %VERSION%
	 *
	 * @return string Custom post type slug.
	 * @throws MustExtendSlug When the default slug has not been extended.
	 */
	protected function get_slug() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtendSlug::default_slug( self::SLUG );
		}

		return static::SLUG;
	}

	/**
	 * Include our custom messages to use when the CPT is updated.
	 *
	 * @author Jeremy Pry
	 * @since  %VERSION%
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
	 * Get the arguments that configure the custom post type.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	abstract protected function get_arguments();

	/**
	 * Get the array of messages to use when updating.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 * @return array
	 */
	abstract protected function get_messages();
}
