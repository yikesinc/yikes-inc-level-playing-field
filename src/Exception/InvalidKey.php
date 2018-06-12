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
 * Class InvalidKey
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class InvalidKey extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of an exception when an empty key is provided.
	 *
	 * @since %VERSION%
	 *
	 * @param string $function The name of the calling function or method.
	 *
	 * @return InvalidKey
	 */
	public static function empty_key( $function ) {
		$message = sprintf( 'The key for "%s" cannot be empty.', $function );

		return new static( $message );
	}
}
