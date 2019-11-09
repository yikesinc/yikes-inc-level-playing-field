<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Comment;

use Yikes\LevelPlayingField\Exception\InvalidCommentID;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;
use Yikes\LevelPlayingField\Messaging\ApplicantMessaging;
/**
 * Class ApplicantMessageRepository
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ApplicantMessageRepository {

	/**
	 * Find the Applicant Message Comment with a given post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The comment ID.
	 *
	 * @return ApplicantMessage
	 * @throws InvalidCommentID If the comment for the requested ID was not found or is not valid.
	 */
	public function find( $id ) {
		$comment = get_comment( $id );
		if ( null === $comment || isset( $comment->type ) && ApplicantMessage::TYPE !== $comment->type ) {
			throw InvalidCommentID::from_id( $id );
		}

		return new ApplicantMessage( $comment );
	}

	/**
	 * Find all the comments for a given $post_id.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $post_id The post ID.
	 *
	 * @return ApplicantMessage[]
	 */
	public function find_all( $post_id ) {
		$args = [
			'post_id' => $post_id,
			'type'    => ApplicantMessage::TYPE,
			'fields'  => 'ids',
			'orderby' => 'comment_date',
			'order'   => 'ASC',
		];

		$query    = new \WP_Comment_Query( $args );
		$comments = [];
		foreach ( $query->comments as $comment ) {
			$comments[ $comment ] = new ApplicantMessage( $comment );
		}

		return $comments;
	}

	/**
	 * Find all the unread comments from applicants.
	 * Comments with status of hold is an unread
	 * applicant message.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $post_id The post ID.
	 *
	 * @return ApplicantMessage[]
	 */
	public function find_new_applicant_messages( $post_id = '' ) {
		$args = [
			'status' => 'hold',
			'type'   => ApplicantMessage::TYPE,
			'fields' => 'ids',
		];

		if ( ! empty( $post_id ) ) {
			$args['post_id'] = $post_id;
		}

		$query    = new \WP_Comment_Query( $args );
		$comments = [];
		foreach ( $query->comments as $comment ) {
			$comments[ $comment ] = new ApplicantMessage( $comment );
		}

		return $comments;
	}
}
