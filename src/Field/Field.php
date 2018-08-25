<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Exception\InvalidField;

interface Field {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render();

	/**
	 * Validate the submission for the given field.
	 *
	 * @since %VERSION%
	 *
	 * @param array $data The submission data to use for validation.
	 *
	 * @return mixed The validated value.
	 * @throws InvalidField When the submission isn't valid.
	 */
	public function validate_submission( $data );

	/**
	 * Set the parent field object for this field.
	 *
	 * @since %VERSION%
	 *
	 * @param Field $field The parent field object.
	 */
	public function set_parent( Field $field );

	/**
	 * Whether the field is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	public function is_repeatable();
}
