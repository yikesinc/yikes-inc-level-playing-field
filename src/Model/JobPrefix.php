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
 * Trait JobPrefix
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait JobPrefix {

	/**
	 * Add the application meta prefix to a string.
	 *
	 * @since %VERSION%
	 *
	 * @param string $string The string to prefix.
	 *
	 * @return string The prefixed string.
	 */
	private function meta_prefix( $string ) {
		return JobMeta::META_PREFIX . $string;
	}
}
