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
 * Class InvalidProperty
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class InvalidProperty extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of the class when a property cannot be modified.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property that cannot be modified.
	 *
	 * @return static
	 */
	public static function cannot_modify( $property ) {
		return new static( sprintf(
			'The property "%s" cannot be modified.',
			$property
		) );
	}
}
