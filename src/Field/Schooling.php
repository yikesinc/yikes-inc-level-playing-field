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
use Yikes\LevelPlayingField\Model\ApplicantMetaDropdowns;

/**
 * Class Schooling
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Schooling extends RepeatableField {

	use ApplicantMetaDropdowns;

	/** @var string */
	protected $class_base = 'schooling';

	/**
	 * Get the array of default fields.
	 *
	 * This should return a multi-dimensional array of field data which will
	 * be used to construct Field objects.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_default_fields() {
		/**
		 * Filter the default schooling fields.
		 *
		 * @param array $fields Array of schooling fields.
		 */
		return apply_filters( 'lpf_field_schooling_fields', [
			ApplicantMeta::INSTITUTION => [
				'label' => esc_html__( 'Institution', 'level-playing-field' ),
			],
			ApplicantMeta::TYPE        => [
				'label'    => esc_html__( 'Institution Type', 'level-playing-field' ),
				'callback' => $this->get_schooling_callback(),
				'options'  => $this->get_schooling_options(),
			],
			ApplicantMeta::YEAR        => [
				'label' => esc_html__( 'Graduation Year', 'level-playing-field' ),
				'class' => Types::YEAR,
			],
			ApplicantMeta::MAJOR       => [
				'label' => esc_html__( 'Major', 'level-playing-field' ),
			],
			ApplicantMeta::DEGREE      => [
				'label' => esc_html__( 'Degree', 'level-playing-field' ),
			],
		] );
	}

	/**
	 * Render the grouping label for the sub-fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since 1.0.0
	 */
	protected function render_grouping_label() {
		printf(
			'<legend class="lpf-field-schooling lpf-input-label">%s</legend>',
			esc_html__( 'Schooling:', 'level-playing-field' )
		);
	}

	/**
	 * Render the label for the repeatable fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since 1.0.0
	 */
	protected function render_repeatable_field_label() {
		printf(
			'<div class="lpf-field-schooling lpf-fieldset-label">%1$s <span class="lpf-fieldset-number">%2$s</span></div>',
			esc_html__( 'School', 'level-playing-field' ),
			esc_html__( '1', 'level-playing-field' )
		);
	}

	/**
	 * Get a callback for generating a new Schooling field.
	 *
	 * @since 1.0.0
	 * @return \Closure
	 */
	private function get_schooling_callback() {
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
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_add_new_label() {
		return esc_html_x( 'School', 'for "add new" button', 'level-playing-field' );
	}
}
