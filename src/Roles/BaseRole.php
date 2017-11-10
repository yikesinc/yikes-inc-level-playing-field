<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;

/**
 * Class BaseRole
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseRole implements Service {

	const SLUG = '_baserole_';

	/**
	 * Register our methods with WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_role' ), 20 );
	}

	/**
	 * Register the custom role and add capabilities to existing roles.
	 *
	 * @since %VERSION%
	 */
	public function register_role() {
		add_role( $this->get_slug(), $this->get_title(), $this->get_caps() );
	}

	/**
	 * Get the slug to use for the custom post type.
	 *
	 * @since %VERSION%
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
	 * Get the localized title for the role.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_title();

	/**
	 * Get the capability array for the role.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_caps();
}
