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

use Yikes\LevelPlayingField\Registerable;

/**
 * Interface Asset.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface Asset extends Registerable {

	/**
	 * Enqueue the asset.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue();

	/**
	 * Dequeue the asset.
	 *
	 * @since 0.2.7
	 *
	 * @return void
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
