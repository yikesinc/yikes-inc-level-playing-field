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
use Yikes\LevelPlayingField\Uninstallable;

/**
 * Class BaseRole
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseRole implements Service, Uninstallable {

	const SLUG = '_baserole_';

	/**
	 * Register our methods with WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_role' ], 20 );
	}

	/**
	 * Register the custom role and add capabilities to existing roles.
	 *
	 * @since 1.0.0
	 */
	public function register_role() {
		add_role( $this->get_slug(), $this->get_title(), $this->get_caps() );
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
	 * Remove role.
	 */
	public function uninstall() {
		remove_role( static::SLUG );
	}

	/**
	 * Get the localized title for the role.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function get_title();

	/**
	 * Get the capability array for the role.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract protected function get_caps();
}
