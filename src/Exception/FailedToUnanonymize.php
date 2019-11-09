<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Exception;

use RuntimeException;

/**
 * Class FailedToUnanonymize
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class FailedToUnanonymize extends RuntimeException implements Exception {

	/**
	 * Create a new exception instance when the current user is not capable of unanonymizing.
	 *
	 * @since 1.0.0
	 * @return static
	 */
	public static function not_capable() {
		return new static(
			sprintf( 'You are not capable of unanonymizing applicants.' )
		);
	}
}
