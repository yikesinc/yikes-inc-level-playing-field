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
 * Class ApplicantEmail.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 */
abstract class ApplicantEmail extends BaseEmail {

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
	 * @since 1.0.0
	 *
	 * @param mixed  $applicant An applicant object or applicant post ID.
	 * @param string $comment   The content of the comment.
	 */
	public function __construct( $applicant, $comment = '' ) {
		$this->set_applicant( $applicant );
		$this->set_comment( $comment );
	}

	/**
	 * Instantiate the Applicant object and assign it to the class,
	 *
	 * @param mixed $applicant An applicant object or applicant post ID.
	 */
	protected function set_applicant( $applicant ) {

		if ( is_numeric( $applicant ) ) {
			$applicant = new Applicant( get_post( $applicant ) );
		}
		$this->applicant = $applicant;
	}

	/**
	 * Prep the comment for use in email.
	 *
	 * @uses stripslashes() to remove slashes from the comment.
	 * @uses nl2br() to convert \r\n's to <br>'s for use in an HTML email.
	 *
	 * @param string $comment The raw comment to/from the applicant.
	 */
	protected function set_comment( $comment ) {
		$this->comment = nl2br( stripslashes( $comment ) );
	}

	/**
	 * Get the HTML link to the applicant's messaging page.
	 *
	 * @since 1.0.0
	 *
	 * @return string The HTML of message with the URL appended.
	 */
	abstract protected function get_messaging_link();
}
