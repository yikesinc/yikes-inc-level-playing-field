<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

/**
 * Interface Registerable.
 *
 * An object that can be `register()`ed.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface Registerable {

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register();
}
