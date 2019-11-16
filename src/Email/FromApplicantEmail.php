<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Email;

use Yikes\LevelPlayingField\Settings\AdditionalEmailRecipients;
use Yikes\LevelPlayingField\Settings\EmailRecipientRoles;
use Yikes\LevelPlayingField\Transient\TransientKeys;

/**
 * Class ApplicantMessageFromApplicantEmail.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
abstract class FromApplicantEmail extends ApplicantEmail {

	const EMAIL_TYPE = 'message-from-applicant';

	/**
	 * Get the recipient's email address.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	protected function recipient() {
		// Fetch the CSV list of email addresses.
		$recipients = array_map( 'trim', explode( ',', ( new AdditionalEmailRecipients() )->get() ) );

		// Fetch the enabled roles for email addresses.
		$recipient_roles = array_filter( ( new EmailRecipientRoles() )->get() );

		foreach ( $recipient_roles as $role => $enabled ) {
			$recipients = array_merge( $recipients, $this->get_recipients_by_role( $role ) );
		}

		// Return only unique email addresses.
		return array_unique( array_filter( $recipients, 'is_email' ) );
	}

	/**
	 * Get the HTML link to the applicant's admin edit page.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML of message with the URL appended.
	 */
	protected function get_messaging_link() {
		$url  = add_query_arg( [
			'post'   => $this->applicant->get_id(),
			'action' => 'edit',
		], admin_url( 'post.php' ) );
		$link = "<br><br><a href='" . esc_url( $url ) . "'>" . __( 'Click here to view the applicant.', 'level-playing-field' ) . '</a>';
		return $link;
	}

	/**
	 * Fetch all of the email addresses defined by the settings AdditionalEmailRecipients and EmailRecipientRoles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $role The role we're fetching emails for.
	 *
	 * @return array $recipients An array of email recipients.
	 */
	private function get_recipients_by_role( $role ) {
		$email_addresses = get_transient( TransientKeys::EMAILS_TRANSIENT . $role );
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

		set_transient( TransientKeys::EMAILS_TRANSIENT . $role, $recipients, WEEK_IN_SECONDS );

		return $recipients;
	}
}
