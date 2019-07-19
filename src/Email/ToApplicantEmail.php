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
use Yikes\LevelPlayingField\Model\Applicant;

/**
 * Class ApplicantMessageFromApplicantEmail.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
abstract class ToApplicantEmail extends ApplicantEmail {

	const EMAIL_TYPE = 'message-to-applicant';

	/**
	 * Get the recipient's email address.
	 *
	 * @since %VERSION%
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	protected function recipient() {
		return $this->applicant->get_email();
	}

	/**
	 * Get the HTML link to the applicant's messaging page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The HTML of message with the URL appended.
	 */
	protected function get_messaging_link() {
		$url  = $this->applicant->get_messaging_endpoint();
		$link = "<br><br>Please use this link to <a href={$url}>" . __( 'view and respond to messages.', 'yikes-level-playing-field' ) . '</a>';
		return $link;
	}
}
