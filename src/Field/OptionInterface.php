<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class SelectOption
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface OptionInterface {

	/**
	 * Render the current option.
	 *
	 * @since %VERSION%
	 */
	public function render();
}