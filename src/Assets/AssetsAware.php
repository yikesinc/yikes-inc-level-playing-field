<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

/**
 * Interface AssetsAware.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface AssetsAware {

	/**
	 * Set the assets handler to use within this object.
	 *
	 * @since %VERSION%
	 *
	 * @param AssetsHandler $assets Assets handler to use.
	 */
	public function with_assets_handler( AssetsHandler $assets );
}
