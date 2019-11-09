<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

/**
 * Class NoDefault
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class NoDefault extends \LogicException implements Exception {

	/**
	 * Create a new exception when a field needs a default.
	 *
	 * @author Jeremy Pry
	 *
	 * @param string $slug The field slug that needs a default value.
	 *
	 * @return static
	 */
	public static function default_value( $slug ) {
		$message = sprintf(
			/* translators: %s refers to a field's slug */
			__( 'The field "%s" must have a default value.', 'level-playing-field' ),
			$slug
		);

		return new static( $message );
	}
}
