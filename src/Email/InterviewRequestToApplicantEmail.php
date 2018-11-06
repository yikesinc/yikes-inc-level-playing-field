<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Email;

use Yikes\LevelPlayingField\Service;

/**
 * Class InterviewRequestToApplicantEmail.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
class InterviewRequestToApplicantEmail extends ToApplicantEmail {

	const EMAIL_TYPE = 'interview-request';

	/**
	 * Get the email subject.
	 *
	 * @since %VERSION%
	 *
	 * @return string The subject of the email.
	 */
	protected function subject() {
		return __( 'You have been selected for an interview.', 'yikes-level-playing-field' );
	}

	/**
	 * Get the email message.
	 *
	 * @since %VERSION%
	 *
	 * @return string The email's message.
	 */
	protected function message() {
		$message  = $this->subject();
		$message .= '<br>';
		$message .= $this->comment;
		$message .= $this->get_interview_links();
		$message .= $this->get_messaging_link();
		return $message;
	}

	/**
	 * Get the confirmation and cancellation endpoints for an interview.
	 *
	 * @since %VERSION%
	 *
	 * @return string $message HTML links to the endpoints.
	 */
	protected function get_interview_links() {
		// Get the endpoints.
		$confirmation_endpoint = $this->applicant->get_confirmation_endpoint();
		$cancellation_endpoint = $this->applicant->get_cancellation_endpoint();

		// Create some HTML URLs.
		$message  = '<a href="' . esc_url( $confirmation_endpoint ) . '">' . __( 'Click here to unanonymize your information and confirm your interview', 'yikes-level-playing-field' ) . '</a>';
		$message .= '<br>';
		$message .= '<a href="' . esc_url( $cancellation_endpoint ) . '">' . __( 'Click here to decline your interview request', 'yikes-level-playing-field' ) . '</a>';

		return $message;
	}
}
