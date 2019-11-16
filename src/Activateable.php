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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface Activateable {

	/**
	 * Activate the service.
	 *
	 * @since 1.0.0
	 */
	public function activate();
}
