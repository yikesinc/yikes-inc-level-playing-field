<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

use Yikes\LevelPlayingField\Exception\MustExtendSlug;
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
		foreach ( $this->get_additional_role_caps() as $role => $caps ) {
			$_role = get_role( $role );

			// If the role wasn't found, continue to the next role.
			if ( null === $_role ) {
				continue;
			}

			foreach ( $caps as $cap => $grant ) {
				$grant = (bool) $grant;

				// Prevent adding the capability if it's already present.
				if ( array_key_exists( $cap, $_role->capabilities ) && $grant === $_role->capabilities[ $cap ] ) {
					continue;
				}

				$_role->add_cap( $cap, $grant );
			}
		}
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

	/**
	 * Get the additional roles and caps to register.
	 *
	 * This should return an associative array. The keys should be the role slug,
	 * and the value should be an array of capabilities. The capabilities should
	 * also be an associative array. This allows for setting capabilities with
	 * a truthy value, and denying capabilities with a falsey value.
	 *
	 * Example:
	 *
	 * return array(
	 *     'editor' => array(
	 *         'custom_cap'     => true,
	 *         'restricted_cap' => false,
	 *     ),
	 *     'administrator' => array(
	 *         'custom_cap'     => true,
	 *         'restricted_cap' => true,
	 *     ),
	 * );
	 *
	 * If no additional registrations are necessary, just return an empty array.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_additional_role_caps();
}