<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

/**
 * Interface Setting
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface Setting {

	/**
	 * Get the setting value.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get();

	/**
	 * Update the value of the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The new value for the setting.
	 */
	public function update( $value );

	/**
	 * Delete the setting from the DB.
	 *
	 * @since 1.0.0
	 */
	public function delete();
}
