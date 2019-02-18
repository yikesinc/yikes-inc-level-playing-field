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
 * Class Checkbox
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Checkbox extends BaseInput {
	const TYPE = 'checkbox';

	/**
	 * The value for the field.
	 *
	 * Default checkbox values to 1.
	 *
	 * @since %VERSION%
	 * @var int.
	 */
	protected $value = 1;
}
