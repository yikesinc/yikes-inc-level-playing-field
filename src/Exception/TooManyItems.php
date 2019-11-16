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
 * Class TooManyItems
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class TooManyItems extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new exception instance for a post type that is limited.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type The post type.
	 * @param int    $limit     The limit for the post type.
	 *
	 * @return static
	 */
	public static function from_post_type( $post_type, $limit ) {
		$message = sprintf(
			/* translators: %1$s is the post type, %2$d is the item limit */
			_n(
				'%1$s do not support more than %2$d item.',
				'%1$s do not support more than %2$d items.',
				$limit,
				'level-playing-field'
			),
			$post_type,
			$limit
		);

		return new static( $message );
	}
}
