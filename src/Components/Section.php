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

	public function __construct( array $fields ) {

	}
}
