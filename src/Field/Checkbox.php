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
	 * @since %VERSION%
	 * @var $value Default checkbox values to 1.
	 */
	protected $value = 1;
}
