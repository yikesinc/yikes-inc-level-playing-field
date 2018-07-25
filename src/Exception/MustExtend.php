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
class MustExtend extends \LogicException implements Exception {

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
			/* translators: %s refers to the default slug */
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
			/* translators: %s refers to the default tag */
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
			/* translators: %s refers to the default view */
			__( 'The default view "%s" must be extended in a subclass.', 'yikes-level-playing-field' ),
			$view
		);

		return new static( $message );
	}

	/**
	 * Create a new exception when a type needs extended.
	 *
	 * @since %VERSION%
	 *
	 * @param string $type The default type that needs extended.
	 *
	 * @return static
	 */
	public static function default_type( $type ) {
		$message = sprintf(
			/* translators: %s refers to the default type */
			__( 'The default type "%s" must be extended in a subclass.', 'yikes-level-playing-field' ),
			$type
		);

		return new static( $message );
	}
}
