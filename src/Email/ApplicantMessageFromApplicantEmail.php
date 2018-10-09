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
class ApplicantMessageFromApplicantEmail extends ApplicantMessageEmail {

	const EMAIL_TYPE = 'message-from-applicant';

	/**
	 * Get the recipient's email address.
	 *
	 * @since %VERSION%
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	protected function recipient() {

		// Fetch the email of the job manager(s).
		return 'kevin@yikesinc.com';
	}

	/**
	 * Get the email subject.
	 *
	 * @since %VERSION%
	 *
	 * @return string The subject of the email.
	 */
	protected function subject() {
		return __( 'An applicant has sent a message about their application', 'yikes-level-playing-field' );
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
		return $message;
	}
}
