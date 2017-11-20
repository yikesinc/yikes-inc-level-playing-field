<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components;

interface Component {

	/**
	 * Get the key for this component.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_key();

	/**
	 * Determine if this is a repeatable object.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_repeatable();
}
