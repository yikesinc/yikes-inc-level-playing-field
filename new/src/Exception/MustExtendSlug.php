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
 * Class MustExtendSlug
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class MustExtendSlug extends \LogicException {

	/**
	 * Create a new extension when a slug needs extended.
	 *
	 * @author Jeremy Pry
	 *
	 * @param string $slug The default slug that needs extended.
	 *
	 * @return static
	 */
	public static function default_slug( $slug ) {
		$message = sprintf(
			__( 'The default slug "%s" must be extended in a subclass.', 'yikes-level-playing-field' ),
			$slug
		);

		return new static( $message );
	}
}
