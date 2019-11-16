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
 * Class InvalidAnonymousData
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class InvalidAnonymousData extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of the exception for anonmyzed data that is not valid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $data The invalid data.
	 *
	 * @return static
	 */
	public static function from_data( $data ) {
		$message = sprintf(
			'The data "%s" is not valid.',
			$data
		);

		return new static( $message );
	}
}
