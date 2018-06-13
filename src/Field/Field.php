<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

interface Field {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render();
}
