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
	 *
	 * @param string $selected_value The currently selected value.
	 */
	public function render( $selected_value );

	/**
	 * Get the value for the option.
	 *
	 * @since %VERSION%
	 * @return string The option value.
	 */
	public function get_value();
}
