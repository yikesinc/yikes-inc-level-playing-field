<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\ApplicationComponents;

use Yikes\LevelPlayingField\Model\Entity;

/**
 * Interface Component
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Component extends Entity {

	/**
	 * Get the data associated with this entity.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	public function get_data();
}
