<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace Yikes\LevelPlayingField\Assets;

/**
 * Interface AssetsAware.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface AssetsAware {

	/**
	 * Set the assets handler to use within this object.
	 *
	 * @since 0.1.0
	 *
	 * @param AssetsHandler $assets Assets handler to use.
	 */
	public function with_assets_handler( AssetsHandler $assets );
}
