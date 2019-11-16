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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait ApplicationPrefix {

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
		return ApplicationMeta::META_PREFIX . $string;
	}

	/**
	 * Add the application form prefix to a string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string The string to prefix.
	 *
	 * @return string The prefixed string.
	 */
	private function form_prefix( $string ) {
		return ApplicationMeta::FORM_FIELD_PREFIX . $string;
	}
}
