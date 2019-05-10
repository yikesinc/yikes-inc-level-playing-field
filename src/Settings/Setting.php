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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Setting {

	/**
	 * Get the setting value.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	public function get();

	/**
	 * Update the value of the setting.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $value The new value for the setting.
	 */
	public function update( $value );

	/**
	 * Delete the setting from the DB.
	 *
	 * @since %VERSION%
	 */
	public function delete();
}
