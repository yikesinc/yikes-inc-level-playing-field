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
use Yikes\LevelPlayingField\Settings\SettingsManager;

/**
 * Class ApplicantMessageFromApplicantEmail.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
abstract class FromApplicantEmail extends ApplicantEmail {

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
		return ( new SettingsManager() )->fetch_from_applicant_email_recipients();
	}

	/**
	 * Get the HTML link to the applicant's admin edit page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The HTML of message with the URL appended.
	 */
	protected function get_messaging_link() {
		$url  = add_query_arg( [
			'post'   => $this->applicant->get_id(),
			'action' => 'edit',
		], admin_url( 'post.php' ) );
		$link = "<br><br><a href='" . esc_url( $url ) . "'>" . __( 'Click here to view the applicant.', 'yikes-level-playing-field' ) . '</a>';
		return $link;
	}
}
