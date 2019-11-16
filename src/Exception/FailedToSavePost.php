<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

/**
 * Class FailedToSavePost
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class FailedToSavePost extends \RuntimeException implements Exception {

	/**
	 * Creat a new instance of the exception if we failed to save a post.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type   The post type that failed to save.
	 * @param string $reason The reason the save failed.
	 *
	 * @return static
	 */
	public static function from_type( $type, $reason ) {
		$message = sprintf( 'Error saving %1$s. Reason: %2$s', $type, $reason );

		return new static( $message );
	}
}
