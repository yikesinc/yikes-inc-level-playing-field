<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

use LogicException;

/**
 * Class InvalidMethod
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class InvalidMethod extends LogicException implements Exception {

	/**
	 * Create a new instance of this exception from an invalid method.
	 *
	 * @since 1.0.0
	 *
	 * @param string|object $class  The class that doesn't have the method.
	 * @param string        $method The method that is missing.
	 *
	 * @return static
	 */
	public static function from_method( $class, $method ) {
		$class = is_object( $class ) ? get_class( $class ) : $class;
		return new static( sprintf(
			'The class "%s" does not have the method "%s()".',
			$class,
			$method
		) );
	}
}
