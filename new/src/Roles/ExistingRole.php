<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

/**
 * Class ExistingRole
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class ExistingRole extends BaseRole {

	/**
	 * Register the custom role and add capabilities to existing roles.
	 *
	 * @since %VERSION%
	 */
	public function register_role() {
		foreach ( $this->get_role_caps() as $role => $caps ) {
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
	abstract protected function get_role_caps();
}
