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
 * Trait ApplicationPrefix
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait ApplicationPrefix {

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
		return ApplicationMeta::META_PREFIX . $string;
	}

	/**
	 * Add the application form prefix to a string.
	 *
	 * @since %VERSION%
	 *
	 * @param string $string The string to prefix.
	 *
	 * @return string The prefixed string.
	 */
	private function form_prefix( $string ) {
		return ApplicationMeta::FORM_FIELD_PREFIX . $string;
	}
}
