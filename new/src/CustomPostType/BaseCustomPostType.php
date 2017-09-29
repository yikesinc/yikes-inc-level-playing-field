<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\CustomPostType;

use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseCustomPostType.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseCustomPostType implements Service {

	const SLUG = '_basecpt_';

	/**
	 * Register the custom post type.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_post_type' ) );
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
	 * @since 0.1.0
	 *
	 * @return string Custom post type slug.
	 */
	protected function get_slug() {
		return static::SLUG;
	}

	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since 0.1.0
	 *
	 * @return array Array of arguments.
	 */
	abstract protected function get_arguments();
}
