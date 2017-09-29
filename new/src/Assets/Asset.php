<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Yikes\LevelPlayingField\Registerable;

/**
 * Interface Asset.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
interface Asset extends Registerable {

	/**
	 * Enqueue the asset.
	 *
	 * @since 0.1.0
	 */
	public function enqueue();

	/**
	 * Dequeue the asset.
	 *
	 * @since 0.2.7
	 */
	public function dequeue();

	/**
	 * Get the handle of the asset.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_handle();
}
