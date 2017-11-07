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
 * Class MustExtend
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Exception
 * @author  Jeremy Pry
 */
class MustExtend extends \LogicException {

	/**
	 * Create a new exception when a slug needs extended.
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

	/**
	 * Create a new exception when a tag needs extended.
	 *
	 * @since %VERSION%
	 *
	 * @param string $tag The default tag that needs extended.
	 *
	 * @return static
	 */
	public static function default_tag( $tag ) {
		$message = sprintf(
			__( 'The default tag "%s" must be extended in a subclass.', 'yikes-level-playing-field' ),
			$tag
		);

		return new static( $message );
	}

	/**
	 * Create a new exception when a view needs extended.
	 *
	 * @since %VERSION%
	 *
	 * @param string $view The default view that needs extended.
	 *
	 * @return static
	 */
	public static function default_view( $view ) {
		$message = sprintf(
			__( 'The default view "%s" must be extended in a subclass.', 'yikes-level-playing-field' ),
			$view
		);

		return new static( $message );
	}
}
