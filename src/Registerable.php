<?php
/**
 * FILE HEADER
 */

namespace Yikes\LevelPlayingField;

/**
 * Interface Registerable.
 *
 * An object that can be `register()`ed.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface Registerable {

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register();
}
