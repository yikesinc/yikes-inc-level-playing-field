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
 * Class FailedToRegister
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class FailedToRegister extends \LogicException implements Exception {

	/**
	 * Create an instance of this exception when an asset was not registered before being enqueued.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle The asset handle.
	 *
	 * @return FailedToRegister
	 */
	public static function asset_not_registered( $handle ) {
		return new static(
			sprintf(
				'The asset "%s" was not registered before it was enqueued. Make sure to call the register() method during init.',
				$handle
			)
		);
	}
}
