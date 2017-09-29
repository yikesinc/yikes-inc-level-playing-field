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
 * Class InvalidURI.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class InvalidURI extends \InvalidArgumentException implements SpeakingPageException {

	/**
	 * Create a new instance of the exception for a file that is not accessible
	 * or not readable.
	 *
	 * @since 0.1.0
	 *
	 * @param string $uri URI of the file that is not accessible or not
	 *                    readable.
	 *
	 * @return static
	 */
	public static function from_uri( $uri ) {
		$message = sprintf(
			'The View URI "%s" is not accessible or readable.',
			$uri
		);

		return new static( $message );
	}
}
