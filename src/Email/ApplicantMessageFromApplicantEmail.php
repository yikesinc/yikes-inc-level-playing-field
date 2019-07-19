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
 * Class ApplicantMessageFromApplicantEmail.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
class ApplicantMessageFromApplicantEmail extends FromApplicantEmail {

	/**
	 * Get the email subject.
	 *
	 * @since %VERSION%
	 *
	 * @return string The subject of the email.
	 */
	protected function subject() {
		return __( 'An applicant has responded your message', 'yikes-level-playing-field' );
	}

	/**
	 * Get the email message.
	 *
	 * @since %VERSION%
	 *
	 * @return string The email's message.
	 */
	protected function message() {
		$message .= 'An applicant has responded to your message regarding their job application.';
		$message .= '<br><br>';
		$message .= $this->comment;
		$message .= $this->get_messaging_link();
		return $message;
	}
}
