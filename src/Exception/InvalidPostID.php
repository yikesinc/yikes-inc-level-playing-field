<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

/**
 * Class InvalidPostID.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class InvalidPostID extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of the exception for a post ID that is not valid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Post ID that is not valid.
	 * @param string $type The object type that is meant to be used.
	 *
	 * @return static
	 */
	public static function from_id( $id, $type ) {
		$message = sprintf(
			/* translators: %1$s: the post ID. %2$s is a post type */
			__( 'The post ID "%1$s" is not a valid %2$s.', 'level-playing-field' ),
			$id,
			$type
		);

		return new static( $message );
	}
}
