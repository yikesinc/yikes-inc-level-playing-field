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
 * Class Section
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Section implements MaybeRepeatable {

	use Repeatable;

	/**
	 * Section constructor.
	 *
	 * @param array $fields Fields to include with the section.
	 */
	public function __construct( array $fields ) {

	}
}
