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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface OptionInterface {

	/**
	 * Render the current option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $selected_value The currently selected value.
	 */
	public function render( $selected_value );

	/**
	 * Get the value for the option.
	 *
	 * @since 1.0.0
	 * @return string The option value.
	 */
	public function get_value();
}
