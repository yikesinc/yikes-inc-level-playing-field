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
 * Class FailedToLoadView.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class FailedToLoadView extends \RuntimeException implements Exception {

	/**
	 * Create a new instance of the exception if the view file itself created
	 * an exception.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $uri       URI of the file that is not accessible or
	 *                              not readable.
	 * @param \Exception $exception Exception that was thrown by the view file.
	 *
	 * @return static
	 */
	public static function view_exception( $uri, $exception ) {
		$message = sprintf(
			'Could not load the View URI "%1$s". Reason: "%2$s".',
			$uri,
			$exception->getMessage()
		);

		return new static( $message, $exception->getCode(), $exception );
	}
}
