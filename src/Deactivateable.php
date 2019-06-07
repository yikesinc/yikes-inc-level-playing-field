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
 * Interface Deactivateable
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Deactivateable {

	/**
	 * Deactivate the service.
	 *
	 * @since %VERSION%
	 */
	public function deactivate();
}
