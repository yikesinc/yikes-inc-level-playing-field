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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Checkbox extends BaseInput {
	const TYPE = 'checkbox';

	/**
	 * The value for the field.
	 *
	 * Default checkbox values to 1.
	 *
	 * @since 1.0.0
	 * @var int.
	 */
	protected $value = 1;
}
