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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantMessageRepository {

	/**
	 * Find the Applicant Message Comment with a given post ID.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
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
	 * Find all the unread comments.
	 *
	 * @since %VERSION%
	 *
	 * @param  int $post_id The post ID.
	 *
	 * @return ApplicantMessage[]
	 */
	public function find_new_messages_from_applicant() {
		$tmp = remove_filter( 'comments_clauses', [ new ApplicantMessaging(), 'exclude_applicant_messages' ], 10 );
		$tmp = remove_filter( 'comment_feed_where', [ new ApplicantMessaging(), 'exclude_applicant_messages_from_feed_where' ], 10 );

		$args = [
			'status' => 'hold',
			'type'   => ApplicantMessage::TYPE,
			//'count'  => true,
		];
		$query    = new \WP_Comment_Query( $args );

		echo '<pre>'; var_dump($query); echo '</pre>';
		exit;
		$comments = [];
		foreach ( $query->comments as $comment ) {
			$comments[ $comment ] = new ApplicantMessage( $comment );
		}

		return $comments;
	}
}
