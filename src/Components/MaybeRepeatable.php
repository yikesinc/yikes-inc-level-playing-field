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
 * Interface MaybeRepeatable
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface MaybeRepeatable {

	/**
	 * Determine if the current object is repeatable.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_repeatable();
}
