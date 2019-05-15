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
 * Interface Activateable
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Activateable {

	/**
	 * Activate the service.
	 *
	 * @since %VERSION%
	 */
	public function activate();
}
