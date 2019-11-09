<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class Number
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Number extends BaseInput {
	const TYPE     = 'number';
	const SANITIZE = FILTER_SANITIZE_NUMBER_INT;
}
