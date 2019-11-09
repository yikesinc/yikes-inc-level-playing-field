<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Comment;

use WP_Comment;

/**
 * Abstract class Comment.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class Comment {

	const APPROVED     = 1;
	const _PARENT      = 0;
	const AUTHOR_URL   = '';
	const AUTHOR_EMAIL = '';
	const AUTHOR_IP    = '';
	const AGENT        = 'LevelPlayingField';
	const TYPE         = '';

	/**
	 * WP_Comment Object.
	 *
	 * @since 1.0.0
	 *
	 * @var   WP_Comment
	 */
	public $comment;

	/**
	 * Set the $comment object for this class.
	 *
	 * @since 1.0.0
	 *
	 * @param int $comment_id The ID of a WP_Comment object.
	 */
	protected function set_comment( $comment_id ) {
		if ( $comment_id ) {
			$this->comment = get_comment( $comment_id );
		}
	}

	/**
	 * Return the values for creating a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	protected function new_comment() {
		return [
			'comment_post_ID'      => '',
			'comment_author'       => '',
			'comment_author_url'   => $this->author_url(),
			'comment_author_email' => $this->author_email(),
			'comment_content'      => '',
			'comment_type'         => $this->type(),
			'comment_parent'       => $this->parent(),
			'user_id'              => $this->user_id(),
			'comment_author_IP'    => $this->author_IP(),
			'comment_agent'        => $this->agent(),
			'comment_date'         => $this->date(),
			'comment_approved'     => $this->approved(),
		];
	}

	/**
	 * Return the ID of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->comment->comment_ID;
	}

	/**
	 * Return the post ID of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_post_id() {
		return $this->comment->comment_post_ID;
	}

	/**
	 * Return the author of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_author() {
		return $this->comment->comment_author;
	}

	/**
	 * Return the author email of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_author_email() {
		return $this->comment->comment_author_email;
	}

	/**
	 * Return the author URL of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_author_url() {
		return $this->comment->comment_author_url;
	}

	/**
	 * Return the date of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_date() {
		return $this->comment->comment_date;
	}

	/**
	 * Format the date of the WP_Comment object with the given format.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $format The format for PHP's date function.
	 *
	 * @return int
	 */
	public function get_formatted_date( $format = 'F j, Y \a\t h:i a' ) {
		return date( $format, strtotime( $this->comment->comment_date ) );
	}

	/**
	 * Return the GMT date of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_date_gmt() {
		return $this->comment->comment_date_gmt;
	}

	/**
	 * Return the content of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_content() {
		return $this->comment->comment_content;
	}

	/**
	 * Return the type of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->comment->comment_type;
	}

	/**
	 * Return the user ID of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_user_id() {
		return $this->comment->user_id;
	}

	/**
	 * Return the Author IP of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_author_ip() {
		return $this->comment->comment_author_IP;
	}

	/**
	 * Return the agent of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_agent() {
		return $this->comment->comment_agent;
	}

	/**
	 * Return the approved status of the WP_Comment object.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_approved() {
		return $this->comment->comment_approved;
	}

	/**
	 * Return the date for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function date() {
		return current_time( 'mysql' );
	}

	/**
	 * Return the approval status for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	protected function approved() {
		return static::APPROVED;
	}

	/**
	 * Return the parent for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	protected function parent() {
		return static::_PARENT;
	}

	/**
	 * Return the author email for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function author_email() {
		return static::AUTHOR_EMAIL;
	}

	/**
	 * Return the author URL for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function author_url() {
		return static::AUTHOR_URL;
	}

	/**
	 * Return the type for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function type() {
		return static::TYPE;
	}

	/**
	 * Return the IP for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function author_ip() {
		return static::AUTHOR_IP;
	}

	/**
	 * Return the agent for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected function agent() {
		return static::AGENT;
	}

	/**
	 * Return the user ID for a new comment.
	 *
	 * @since  1.0.0
	 *
	 * @return int
	 */
	protected function user_id() {
		return wp_get_current_user()->ID;
	}

	/**
	 * Insert a new comment and assign it to the class.
	 *
	 * @since  1.0.0
	 *
	 * @param array $comment_data The array of comment data to override the defaults.
	 *
	 * @return int
	 */
	public function create_comment( $comment_data ) {

		// Make sure our required fields are present.
		if ( empty( $comment_data['comment_post_ID'] ) || empty( $comment_data['comment_content'] ) || empty( $comment_data['comment_author'] ) ) {
			return false;
		}

		$comment    = array_merge( $this->new_comment(), $comment_data );
		$comment_id = wp_insert_comment( $comment );

		if ( false !== $comment_id ) {
			$this->set_comment( $comment_id );
			return $comment_id;
		}

		return false;
	}

	/**
	 * Delete comments that were created during email errors.
	 *
	 * @since 1.0.0
	 *
	 * @param int $comment The ID of comment.
	 *
	 * @return int
	 */
	public function delete_comment( $comment ) {

		if ( empty( $comment ) ) {
			return false;
		}

		$comment_id = wp_delete_comment( $comment, true );

		return $comment_id;

	}
}
