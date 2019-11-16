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
 * Class InvalidService.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class InvalidService extends \InvalidArgumentException implements Exception {

	/**
	 * Create a new instance of the exception for a service class name that is
	 * not recognized.
	 *
	 * @since 1.0.0
	 *
	 * @param string $service Class name of the service that was not recognized.
	 *
	 * @return static
	 */
	public static function from_service( $service ) {
		$message = sprintf(
			'The service "%s" is not recognized and cannot be registered.',
			is_object( $service )
				? get_class( $service )
				: (string) $service
		);

		return new static( $message );
	}
}
