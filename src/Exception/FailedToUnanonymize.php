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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class FailedToUnanonymize extends RuntimeException implements Exception {

	/**
	 * Create a new exception instance when the current user is not capable of unanonymizing.
	 *
	 * @since %VERSION%
	 * @return static
	 */
	public static function not_capable() {
		return new static(
			sprintf( 'You are not capable of unanonymizing applicants.' )
		);
	}
}
