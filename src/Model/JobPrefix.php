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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait JobPrefix {

	/**
	 * Add the application meta prefix to a string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The string to prefix.
	 *
	 * @return string The prefixed string.
	 */
	private function meta_prefix( $string ) {
		return JobMeta::META_PREFIX . $string;
	}
}
