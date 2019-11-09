<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Interface LimitedEntity
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface LimitedEntity extends Entity {

	/**
	 * Get the number of items the entity is limited to.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_limit();
}
