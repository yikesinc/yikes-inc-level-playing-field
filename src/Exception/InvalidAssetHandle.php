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
 * Class InvalidAssetHandle.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class InvalidAssetHandle extends \InvalidArgumentException implements SpeakingPageException {

	/**
	 * Create a new instance of the exception for a asset handle that is not
	 * valid.
	 *
	 * @since %VERSION%
	 *
	 * @param int $handle Asset handle that is not valid.
	 *
	 * @return static
	 */
	public static function from_handle( $handle ) {
		$message = sprintf(
			'The asset handle "%s" is not valid.',
			$handle
		);

		return new static( $message );
	}
}
