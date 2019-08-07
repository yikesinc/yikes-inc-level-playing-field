<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\REST;

/**
 * Interface Routeable
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Routeable {

	/**
	 * Register the routes.
	 *
	 * @since %VERSION%
	 */
	public function register_routes();
}
