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
 * Class InvalidOption
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class InvalidOption extends InvalidArgumentException implements Exception {

	/**
	 * Create a new Exception instance from an invalid option.
	 *
	 * @since 1.0.0
	 *
	 * @param string|object $option The invalid Option.
	 *
	 * @return InvalidOption
	 */
	public static function from_option( $option ) {
		$message = sprintf(
			'The option "%s" does not implement OptionInterface.',
			is_object( $option ) ? get_class( $option ) : (string) $option
		);

		return new static( $message );
	}
}
