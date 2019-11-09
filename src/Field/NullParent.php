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
 * Class NullParent
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class NullParent extends BaseField {

	/**
	 * NullParent constructor.
	 */
	public function __construct() {
		$this->required = false;
	}

	/**
	 * Render the field.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		// nothing to do here.
	}

	/**
	 * Ensure we have a valid ID for the field.
	 */
	protected function validate_id() {
		return true;
	}

	/**
	 * Validate the submission for the given field.
	 *
	 * @since 1.0.0
	 *
	 * @return null A null placeholder.
	 */
	public function get_sanitized_value() {
		return null;
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_error_type() {
		return '';
	}
}
