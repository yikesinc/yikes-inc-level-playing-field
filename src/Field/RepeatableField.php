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
 * Class RepeatableField.
 *
 * This is a complex field which can have repeatable input.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class RepeatableField extends ComplexField {

	/**
	 * Set up the sub fields for this field.
	 *
	 * @since 1.0.0
	 * @throws InvalidField When an invalid field class is provided through the filter.
	 */
	protected function setup_sub_fields() {
		$this->sub_fields[0] = $this->generate_sub_fields();
	}

	/**
	 * Get the ID base for sub-fields.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_id_base() {
		return parent::get_id_base() . '[0]';
	}

	/**
	 * Render an individual fieldset group.
	 *
	 * @since 1.0.0
	 */
	protected function render_fieldset_group() {
		$last = count( $this->sub_fields ) - 1;
		foreach ( $this->sub_fields as $group => $fields ) {
			$this->render_open_fieldset();
			$this->render_grouping_label();

			$this->render_open_field_container();

			// First field group doesn't get a delete button.
			if ( $group > 0 ) {
				$this->render_delete_button();
			}

			$this->render_repeatable_field_label();
			$this->render_sub_fields( $group );

			// Only render the repeater button for the last fieldset.
			if ( $last === $group ) {
				$this->render_repeater_button();
			}

			$this->render_close_field_container();
			$this->render_close_fieldset();
		}
	}

	/**
	 * Render the opening of a fieldset element.
	 *
	 * @since 1.0.0
	 */
	protected function render_open_fieldset() {
		$classes = [
			'lpf-fieldset',
			"lpf-fieldset-{$this->class_base}",
			'lpf-fieldset-repeatable',
		];

		printf(
			'<fieldset class="%s">',
			esc_attr( join( ' ', $classes ) )
		);
	}

	/**
	 * Render the opening of a field container div tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_open_field_container() {
		$classes = [
			'lpf-fieldset-container',
			"lpf-fieldset-{$this->class_base}-container",
			'lpf-fieldset-repeatable-container',
		];

		printf(
			'<div class="lpf-fieldset-container" class="%s" data-add-new-label="%s">',
			esc_attr( join( ' ', $classes ) ),
			esc_attr( $this->get_add_new_label() )
		);
	}

	/**
	 * Render the delete button.
	 *
	 * @since 1.0.0
	 */
	protected function render_delete_button() {
		print( '<button type="button" class="lpf-delete-button">x</button>' );
	}

	/**
	 * Render the sub-fields.
	 *
	 * @since 1.0.0
	 *
	 * @param int $group The index for the group of fields to render.
	 */
	protected function render_sub_fields( $group = 0 ) {
		/** @var Field $sub_field */
		foreach ( $this->sub_fields[ $group ] as $sub_field ) {
			$sub_field->render();
		}
	}

	/**
	 * Render the closing field container div tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_close_field_container() {
		echo '</div>';
	}

	/**
	 * Render the repeater button.
	 *
	 * @since 1.0.0
	 */
	protected function render_repeater_button() {
		printf(
			'<button type="button" class="lpf-repeat-button">%1$s %2$s</button>',
			esc_html_x( 'Add Another', 'button for adding section in application', 'level-playing-field' ),
			esc_html( $this->get_add_new_label() )
		);
	}

	/**
	 * Get the label to use when rendering the "Add New" button.
	 *
	 * Only needs to be overridden when the field is repeatable.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_add_new_label() {
		return '';
	}

	/**
	 * Render the label for repeatable fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since 1.0.0
	 */
	abstract protected function render_repeatable_field_label();

	/**
	 * Set the data submitted to the field.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data The submitted data for the field.
	 *
	 * @throws InvalidField When the field submission is invalid.
	 */
	public function set_submission( $data ) {
		try {
			$this->raw_value = $data;
			$this->validate_raw_value();
			$this->clone_sub_fields( count( $data ) );

			foreach ( $this->sub_fields as $key => $group ) {
				/**
				 * @var string $name
				 * @var Field  $field
				 */
				foreach ( $group as $name => $field ) {
					$field->set_submission( isset( $data[ $key ][ $name ] ) ? $data[ $key ][ $name ] : '' );
				}
			}
		} catch ( InvalidField $e ) {
			$this->error_message = $e->getMessage();
			throw $e;
		}
	}

	/**
	 * Validate the submission for the given field.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The validated value.
	 * @throws InvalidField When the submission isn't valid.
	 */
	public function get_sanitized_value() {
		$values = [];
		foreach ( $this->sub_fields as $key => $group ) {
			$values[ $key ] = [];
			/** @var Field $field */
			foreach ( $group as $name => $field ) {
				$values[ $key ][ $name ] = $field->get_sanitized_value();
			}
		}

		if ( empty( $values ) ) {
			throw InvalidField::value_invalid( $this->get_label() );
		}

		return $values;
	}

	/**
	 * Clone sub-fields so that each field object can handle its own submission.
	 *
	 * @since 1.0.0
	 *
	 * @param int $total The total number of fields.
	 */
	private function clone_sub_fields( $total ) {
		$current = 1;
		while ( $current < $total ) {
			if ( isset( $this->sub_fields[ $current ] ) ) {
				continue;
			}

			$this->sub_fields[ $current ] = [];

			/** @var Field $field */
			foreach ( $this->sub_fields[0] as $name => $field ) {
				// Set up the new field.
				$new_field = clone $field;

				// Set up the new ID.
				$new_id = str_replace( '[0]', "[{$current}]", $field->get_id() );
				$new_field->set_id( $new_id );

				// Add to the sub fields.
				$this->sub_fields[ $current ][ $name ] = $new_field;
			}

			$current++;
		}
	}

	/**
	 * Validate the raw value.
	 *
	 * Make sure the array is in the right format when it is repeating. We expect to receive
	 * a numerically-indexed array of arrays.
	 *
	 * @since 1.0.0
	 *
	 * @throws InvalidField When the raw value is empty but the field is required.
	 */
	protected function validate_raw_value() {
		parent::validate_raw_value();
		foreach ( $this->raw_value as $key => $value ) {
			if ( ! is_numeric( $key ) || ! is_array( $value ) ) {
				throw InvalidField::value_invalid(
					$this->get_label(),
					'Unexpected format for repeatable complex field.'
				);
			}
		}
	}
}
