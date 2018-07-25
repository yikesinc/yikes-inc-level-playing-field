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
			esc_html__( 'The field class "%s" is not recognized and cannot be used.', 'yikes-level-playing-field' ),
			is_object( $field )
				? get_class( $field )
				: (string) $field
		);

		return new static( $message );
	}

	/**
	 * Create a new instance of the exception for a field ID that is invalid.
	 *
	 * @since %VERSION%
	 *
	 * @param string $id The invalid field ID.
	 *
	 * @return InvalidField
	 */
	public static function invalid_id( $id ) {
		$message = sprintf(
			/* translators: %s represents the field ID */
			esc_html__( 'The ID "%s" is invalid. The ID must be a simple string, or a single depth array', 'yikes-level-playing-field' ),
			$id
		);

		return new static( $message );
	}
}
