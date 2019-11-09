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
use Yikes\LevelPlayingField\Applicant;

/**
 * Class InterviewCancellationFromApplicantEmail.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
class InterviewCancellationFromApplicantEmail extends FromApplicantEmail {

	const EMAIL_TYPE = 'interview-cancellation';

	/**
	 * Get the email subject.
	 *
	 * @since 1.0.0
	 *
	 * @return string The subject of the email.
	 */
	protected function subject() {
		return __( 'An applicant has declined their interview request.', 'level-playing-field' );
	}

	/**
	 * Get the email message.
	 *
	 * @since 1.0.0
	 *
	 * @return string The email's message.
	 */
	protected function message() {
		$message  = $this->subject();
		$message .= '<br>';
		$message .= __( 'Maybe some further instructions that you can message the applicant or re-schedule an interview.', 'level-playing-field' );
		$message .= $this->get_messaging_link();
		return $message;
	}
}
