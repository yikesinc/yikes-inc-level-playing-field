<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components;

/**
 * Interface Component
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Component {

	/**
	 * Get the key for this component.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_key();
}
