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
	 * The base HTML class value.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $class_base = 'complexfield';

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
	 * @var Field[]
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
			$id = $this->id . ( $this->repeatable ? '[0]' : '' ) . "[{$field}]";

			// Instantiate the sub field.
			$this->sub_fields[ $field ] = new $settings['class'](
				$id,
				$settings['label'],
				$classes,
				(bool) $settings['required']
			);

			// Ensure the class extends the Field interface.
			if ( ! ( $this->sub_fields[ $field ] instanceof Field ) ) {
				throw InvalidField::from_field( $settings['class'] );
			}

			// Assign the current object as the field parent.
			$this->sub_fields[ $field ]->set_parent( $this );
		}
	}

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$this->render_open_fieldset();
		$this->render_grouping_label();
		$this->render_sub_fields();
		$this->render_close_fieldset();
	}

	/**
	 * Render the opening of a fieldset element.
	 *
	 * @since %VERSION%
	 */
	protected function render_open_fieldset() {
		$classes = [ 'lpf-fieldset', "lpf-fieldset-{$this->class_base}" ];
		if ( $this->repeatable ) {
			$classes[] = 'lpf-fieldset-repeatable';
		}

		printf(
			'<div class="lpf-field-container"><fieldset class="%s" %s>',
			esc_attr( join( ' ', $classes ) ),
			$this->repeatable ? sprintf( 'data-add-new-label="%s"', esc_attr( $this->get_add_new_label() ) ) : ''
		);
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
	 * Render the closing fieldset tag.
	 *
	 * @since %VERSION%
	 */
	protected function render_close_fieldset() {
		echo '</fieldset></div>';
	}

	/**
	 * Get the array of classes to merge in with the default field classes.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_classes() {
		return [ "lpf-field-{$this->class_base}" ];
	}

	/**
	 * Get the label to use when rendering the "Add New" button.
	 *
	 * Only needs to be overridden when the field is repeatable.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_add_new_label() {
		return '';
	}

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
