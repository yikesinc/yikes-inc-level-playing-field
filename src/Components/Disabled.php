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
 * Trait Disabled
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait Disabled {

	/**
	 * Whether the object is disabled.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $disabled;

	/**
	 * Determine if the current object is disabled.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_disabled() {
		return $this->disabled;
	}
}
