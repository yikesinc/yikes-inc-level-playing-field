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
 * Trait Repeatable
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait Repeatable {

	/**
	 * Whether the object is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $repeatable;

	/**
	 * Determine whether the object is repeatable.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_repeatable() {
		return $this->repeatable;
	}
}
