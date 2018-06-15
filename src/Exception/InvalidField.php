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
 * Class InvalidField
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class InvalidField extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of the exception for a field class name that is
	 * not recognized.
	 *
	 * @since %VERSION%
	 *
	 * @param string $field Class name of the service that was not recognized.
	 *
	 * @return static
	 */
	public static function from_field( $field ) {
		$message = sprintf(
			/* translators: %s represents the class name */
			esc_html__( 'The field "%s" is not recognized and cannot be used.' ),
			is_object( $field )
				? get_class( $field )
				: (string) $field
		);

		return new static( $message );
	}
}
