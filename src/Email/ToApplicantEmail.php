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
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
abstract class ToApplicantEmail extends ApplicantEmail {

	const EMAIL_TYPE = 'message-to-applicant';

	/**
	 * Get the recipient's email address.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	protected function recipient() {
		return $this->applicant->get_email_for_send();
	}

	/**
	 * Get the HTML link to the applicant's messaging page.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML of message with the URL appended.
	 */
	protected function get_messaging_link() {
		$url  = $this->applicant->get_messaging_endpoint();
		$link = "<br><br><a href={$url}>" . __( 'Click here to view your correspondence.', 'level-playing-field' ) . '</a>';
		return $link;
	}
}
