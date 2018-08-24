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
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
class ApplicantMessage extends Comment {

	const TYPE = 'applicant_message';

	/**
	 * Set the class' comment object if provided.
	 *
	 * @since %VERSION%
	 *
	 * @param int $comment_id Comment object to instantiate a Comment model from.
	 */
	public function __construct( $comment_id = 0 ) {
		$this->set_comment( $comment_id );
	}
}
