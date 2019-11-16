<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Comment;

/**
 * Class ApplicantMessage.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
class ApplicantMessage extends Comment {

	const TYPE             = 'applicant_message';
	const ADMIN_AUTHOR     = 'Job Manager';
	const APPLICANT_AUTHOR = 'Applicant';

	/**
	 * Set the class' comment object if provided.
	 *
	 * @since 1.0.0
	 *
	 * @param int $comment_id Comment object to instantiate a Comment model from.
	 */
	public function __construct( $comment_id = 0 ) {
		$this->set_comment( $comment_id );
	}
}
