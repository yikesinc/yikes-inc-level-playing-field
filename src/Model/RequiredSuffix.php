<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Trait RequiredSuffix
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait RequiredSuffix {

	/**
	 * Add _required as a suffix to a string.
	 *
	 * @since %VERSION%
	 *
	 * @param string $string The string to modify.
	 *
	 * @return string The modified string.
	 */
	private function required_suffix( $string ) {
		return $string . '_required';
	}
}
