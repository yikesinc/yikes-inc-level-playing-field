<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Model\ApplicantMeta;

/**
 * Class Languages
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class Languages extends RepeatableField {

	use ApplicantMetaDropdowns;

	/**
	 * Get the array of default fields.
	 *
	 * This should return a multi-dimensional array of field data which will
	 * be used to construct Field objects.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_fields() {
		/**
		 * Filter the default Language fields.
		 *
		 * @param array $fields Array of language fields.
		 */
		return apply_filters( 'lpf_field_language_fields', [
			ApplicantMeta::LANGUAGE    => [
				'label' => esc_html__( 'Language', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::PROFICIENCY => [
				'label'    => esc_html__( 'Proficiency', 'yikes-level-playing-field' ),
				'callback' => $this->get_proficiency_callback(),
				'options'  => $this->get_language_options(),
			],
		] );
	}

	/**
	 * Render the grouping label for the sub-fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since %VERSION%
	 */
	protected function render_grouping_label() {
		printf(
			'<legend class="lpf-field-language lpf-input-label">%s</legend>',
			esc_html__( 'Language and Proficiency:', 'yikes-level-playing-field' )
		);
	}

	/**
	 * Get a callback for generating a new Proficiency field.
	 *
	 * @since %VERSION%
	 * @return \Closure
	 */
	private function get_proficiency_callback() {
		return function( $id_base, $field, $classes, $settings ) {
			$options = [];
			foreach ( $settings['options'] as $value => $label ) {
				$options[] = new SelectOption( $label, $value );
			}

			return new Select(
				"{$id_base}[{$field}]",
				$settings['label'],
				$classes,
				(bool) $settings['required'],
				$options
			);
		};
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
		return esc_html_x( 'Language', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
