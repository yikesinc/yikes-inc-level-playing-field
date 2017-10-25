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
 * Class AnonymousCustomPostTypeEntity
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class AnonymousCustomPostTypeEntity extends CustomPostTypeEntity {

	/**
	 * Return the list of properties that should be anonymized.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_anonymous_properties();
}
