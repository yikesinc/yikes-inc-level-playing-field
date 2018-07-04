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
	 * Whether the field is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $repeatable = false;

	/**
	 * Array of sub-fields.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $sub_fields = [];

	/**
	 * BaseField constructor.
	 *
	 * @param string $id       The field ID.
	 * @param string $label    The field label.
	 * @param array  $classes  Array of field classes.
	 * @param bool   $required Whether the field is required.
	 *
	 * @throws InvalidField When the provided ID is invalid.
	 */
	public function __construct( $id, $label, array $classes, $required = true ) {
		parent::__construct( $id, $label, $classes, $required );
		$this->generate_sub_fields();
	}

	/**
	 * Generate the sub-field objects for this field.
	 *
	 * @since %VERSION%
	 *
	 * @throws InvalidField When an invalid field class is provided through the filter.
	 */
	protected function generate_sub_fields() {
		$classes        = array_merge( $this->classes, $this->get_classes() );
		$default_fields = $this->get_default_fields();
		foreach ( $default_fields as $field => $settings ) {
			$settings = wp_parse_args( $settings, [
				'label'    => ucwords( str_replace( [ '_', '-' ], ' ', $field ) ),
				'class'    => Types::TEXT,
				'required' => true,
			] );

			// Set up the field ID, depending on whether the field is repeatable.
			$id = $this->id . ( $this->repeatable ? '[]' : '' ) . "[{$field}]";

			// Instantiate the sub field.
			$this->sub_fields[] = new $settings['class'](
				$id,
				$settings['label'],
				$classes,
				(bool) $settings['required']
			);

			// Ensure the class extends the Field interface.
			if ( ! ( end( $this->sub_fields ) instanceof Field ) ) {
				throw InvalidField::from_field( $settings['class'] );
			}
		}
	}

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$this->render_grouping_label();
		$this->render_sub_fields();
	}

	/**
	 * Render the sub-fields.
	 *
	 * @since %VERSION%
	 */
	protected function render_sub_fields() {
		/** @var Field $sub_field */
		foreach ( $this->sub_fields as $sub_field ) {
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
