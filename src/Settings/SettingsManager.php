<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Service;

/**
 * Class SettingsManager.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class SettingsManager implements Service {

	const EMAILS_TRANSIENT_PREFIX = 'lpf_from_applicant_emails_';

	/**
	 * Register the hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'wp_ajax_save_settings', [ $this, 'save' ] );

		// Email transient busters.
		add_action( 'set_user_role', [ $this, 'maybe_bust_email_transient' ], 10, 1 );
		add_action( 'delete_user', [ $this, 'maybe_bust_email_transient' ], 10, 1 );
		add_action( 'user_register', [ $this, 'maybe_bust_email_transient' ], 10, 1 );
	}

	/**
	 * Fetch all of the email addresses defined by the settings AdditionalEmailRecipients and EmailRecipientRoles.
	 *
	 * @since %VERSION%
	 * @return array $recipients An array of email recipients.
	 */
	public function fetch_from_applicant_email_recipients() {
		// Fetch the CSV list of email addresses.
		$recipients = explode( ',', ( new AdditionalEmailRecipients() )->get() );

		// Fetch the enabled roles for email addresses.
		$recipient_roles = array_filter( ( new EmailRecipientRoles() )->get() );

		foreach ( $recipient_roles as $role => $enabled ) {
			$recipients = array_merge( $recipients, $this->get_recipients_by_role( $role ) );
		}

		// Return only unique email addresses.
		return array_unique( array_filter( $recipients, 'is_email' ) );
	}

	/**
	 * Fetch all of the email addresses defined by the settings AdditionalEmailRecipients and EmailRecipientRoles.
	 *
	 * @since %VERSION%
	 *
	 * @param  string $role       The role we're fetching emails for.
	 * @return array  $recipients An array of email recipients.
	 */
	private function get_recipients_by_role( $role ) {
		$email_addresses = get_transient( static::EMAILS_TRANSIENT_PREFIX . $role );
		if ( false !== $email_addresses ) {
			return $email_addresses;
		}

		$recipients = [];
		$user_ids   = ( new \WP_User_Query( [
			'role'   => $role,
			'number' => '-1',
			'fields' => 'email',
		] ) )->get_results();

		if ( ! empty( $user_ids ) ) {
			$recipients = array_map( function( $id ) {
				return get_userdata( $id )->user_email;
			}, $user_ids );
		}

		set_transient( static::EMAILS_TRANSIENT_PREFIX . $role, $recipients, WEEK_IN_SECONDS );

		return $recipients;
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
	public function maybe_bust_email_transient( $user_id ) {

		$roles = get_userdata( $user_id )->roles;

		// Fetch the enabled roles for email addresses.
		$email_recipient_roles = array_filter( ( new Settings() )->get_setting( EmailRecipientRoles::SLUG ) );

		foreach ( $email_recipient_roles as $role => $enabled ) {
			if ( isset( array_flip( $roles )[ $role ] ) ) {
				delete_transient( static::EMAILS_TRANSIENT_PREFIX . $role );
			}
		}
	}

	/**
	 * AJAX handler to save our settings.
	 *
	 * @since %VERSION%
	 */
	public function save() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'save_settings', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'yikes-level-playing-field' ),
			], 400 );
		}

		// Fetch our current settings.
		$settings = new Settings();

		// Get the posted settings.
		$posted_settings = wp_unslash( $_POST['settings'] );

		foreach ( $posted_settings as $key => $value ) {
			$settings->$key = $value;
		}

		$settings->save();

		wp_send_json_success( [
			'reason' => __( 'Success: Settings Saved.', 'yikes-level-playing-field' ),
		], 200 );
	}
}
