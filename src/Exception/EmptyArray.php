<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

use InvalidArgumentException;

/**
 * Class EmptyArray
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class EmptyArray extends InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of an exception when an empty array is provided.
	 *
	 * @since 1.0.0
	 *
	 * @param string $function The function name.
	 *
	 * @return static
	 */
	public static function from_function( $function ) {
		$message = sprintf( 'Function %s cannot receive an empty array.', $function );

		return new static( $message );
	}
}
