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
		$role = get_role( $this->get_slug() );

		// Since other code could interfere with roles, trigger an error instead of throwing an Exception.
		if ( null === $role ) {
			$message = sprintf( 'The %s role was not found.', $role );
			trigger_error( esc_html( $message ), E_USER_NOTICE );

			return;
		}

		foreach ( $this->get_caps() as $cap => $grant ) {
			$grant = (bool) $grant;

			// Prevent adding the capability if it's already present.
			if ( array_key_exists( $cap, $role->capabilities ) && $grant === $role->capabilities[ $cap ] ) {
				continue;
			}

			$role->add_cap( $cap, $grant );
		}
	}

	/**
	 * Get the localized title for the role.
	 *
	 * This isn't used for existing roles.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_title() {
		return '';
	}
}
