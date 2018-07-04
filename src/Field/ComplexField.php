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

/**
 * Abstract Class ComplexField
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class ComplexField extends BaseField {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 *
	 * @throws InvalidField When an invalid field class is provided through the filter.
	 */
	public function render() {
		$classes        = array_merge( $this->classes, $this->get_classes() );
		$default_fields = $this->get_default_fields();

		// Generate the sub-fields for the address.
		$sub_fields = [];
		foreach ( $default_fields as $field => $settings ) {
			$settings = wp_parse_args( $settings, [
				'label'    => ucwords( str_replace( [ '_', '-' ], ' ', $field ) ),
				'class'    => Types::TEXT,
				'required' => true,
			] );

			$sub_fields[] = new $settings['class'](
				"{$this->id}[{$field}]",
				$settings['label'],
				$classes,
				(bool) $settings['required']
			);

			// Ensure the class extends the Field interface.
			if ( ! ( end( $sub_fields ) instanceof Field ) ) {
				throw InvalidField::from_field( $settings['class'] );
			}
		}

		// Render the grouping label.
		$this->render_grouping_label();

		/**
		 * Render the sub-fields.
		 *
		 * @var Field $sub_field
		 */
		foreach ( $sub_fields as $sub_field ) {
			$sub_field->render();
		}
	}

	/**
	 * Get the array of classes to merge in with the default field classes.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_classes();

	/**
	 * Get the array of default fields.
	 *
	 * This should return a multi-dimensional array of field data which will
	 * be used to construct Field objects.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_default_fields();

	/**
	 * Render the grouping label for the sub-fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since %VERSION%
	 */
	abstract protected function render_grouping_label();
}
