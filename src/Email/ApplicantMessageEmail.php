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
abstract class ApplicantMessageEmail extends BaseEmail {

	/**
	 * The applicant object.
	 *
	 * @var object $applicant The applicant object.
	 */
	protected $applicant;

	/**
	 * The content of the comment.
	 *
	 * @var string $comment The comment sent to the applicant.
	 */
	protected $comment;

	/**
	 * Fetch the applicant object and assign it to the $applicant property.
	 *
	 * @since %VERSION%
	 *
	 * @param int    $applicant_id The post ID of the applicant.
	 * @param string $comment      The content of the comment.
	 */
	public function __construct( $applicant_id, $comment ) {
		$this->applicant = new Applicant( get_post( $applicant_id ) );
		$this->comment   = $comment;
	}

	/**
	 * Get the recipient's email address.
	 *
	 * @since %VERSION%
	 *
	 * @return mixed An array or comma-separated list of email addresses.
	 */
	abstract protected function recipient();

	/**
	 * Get the email subject.
	 *
	 * @since %VERSION%
	 *
	 * @return string The subject of the email.
	 */
	abstract protected function subject();

	/**
	 * Get the email message.
	 *
	 * @since %VERSION%
	 *
	 * @return string The email's message.
	 */
	abstract protected function message();
}
