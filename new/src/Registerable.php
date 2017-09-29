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
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface Registerable {

	/**
	 * Register the current Registerable.
	 *
	 * @since 0.1.0
	 */
	public function register();
}
