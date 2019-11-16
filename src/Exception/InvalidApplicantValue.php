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
 * Class InvalidApplicantValue
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class InvalidApplicantValue extends InvalidArgumentException implements Exception {

	/**
	 * Create new exception instance when an invalid value is given for a property.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 * @param string $value    The attempted value of the property.
	 *
	 * @return static
	 */
	public static function property_value( $property, $value ) {
		return new static( sprintf(
			'The value "%s" is not valid for the "%" property.',
			(string) $value,
			$property
		) );
	}

	/**
	 * Create a new exception instance when a property has no sanitization setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 *
	 * @return InvalidApplicantValue
	 */
	public static function no_sanitization( $property ) {
		return new static( sprintf(
			'The "%s" property does not have a sanitization setting.',
			$property
		) );
	}
}
