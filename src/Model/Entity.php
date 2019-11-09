<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Interface Entity.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface Entity {

	/**
	 * Return the entity ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int Entity ID.
	 */
	public function get_id();
}
