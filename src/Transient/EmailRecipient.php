<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Transient;

use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Settings\EmailRecipientRoles;

/**
 * Class EmailRecipient
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class EmailRecipient implements Service {

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$bust_transients = function( $user_id ) {
			$this->maybe_bust_email_transient( $user_id );
		};

		add_action( 'set_user_role', $bust_transients );
		add_action( 'delete_user', $bust_transients );
		add_action( 'user_register', $bust_transients );
	}

	/**
	 * Delete the LPF emails transient.
	 *
	 * If a user is added, deleted, or their role has changed and they have one of the assigned roles, delete our LPF emails transient.
	 *
	 * @since %VERSION%
	 *
	 * @param int $user_id The user's ID.
	 */
	private function maybe_bust_email_transient( $user_id ) {

		$roles = get_userdata( $user_id )->roles;

		// Fetch the enabled roles for email addresses.
		$email_recipient_roles = array_filter( ( new EmailRecipientRoles() )->get() );

		foreach ( $email_recipient_roles as $role => $enabled ) {
			if ( isset( array_flip( $roles )[ $role ] ) ) {
				delete_transient( TransientKeys::EMAILS_TRANSIENT . $role );
			}
		}
	}
}
