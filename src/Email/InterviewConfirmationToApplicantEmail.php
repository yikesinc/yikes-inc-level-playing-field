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
 * Class InterviewConfirmationToApplicantEmail.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
class InterviewConfirmationToApplicantEmail extends ToApplicantEmail {

	const EMAIL_TYPE = 'interview-confirmation';

	/**
	 * Get the email subject.
	 *
	 * @since %VERSION%
	 *
	 * @return string The subject of the email.
	 */
	protected function subject() {
		return __( 'You have confirmed your interview.', 'yikes-level-playing-field' );
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
		$message .= __( 'Maybe show the interview details again...', 'yikes-level-playing-field' );
		$message .= __( 'Maybe some further confirmation that your personal information is now unanonymized.', 'yikes-level-playing-field' );
		$message .= $this->get_messaging_link();
		return $message;
	}
}
