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
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface Entity {

	/**
	 * Return the entity ID.
	 *
	 * @since %VERSION%
	 *
	 * @return int Entity ID.
	 */
	public function get_id();
}
