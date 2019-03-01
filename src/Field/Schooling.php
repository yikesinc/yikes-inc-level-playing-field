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
 * Class Schooling
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Schooling extends RepeatableField {

	/** @var string */
	protected $class_base = 'schooling';

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
		 * Filter the default schooling fields.
		 *
		 * @param array $fields Array of schooling fields.
		 */
		return apply_filters( 'lpf_field_schooling_fields', [
			ApplicantMeta::INSTITUTION => [
				'label' => esc_html__( 'Institution', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::TYPE        => [
				'label'    => esc_html__( 'Institution Type', 'yikes-level-playing-field' ),
				'class'    => Types::SELECT,
				'required' => false,
			],
			ApplicantMeta::TYPE        => [
				'label'    => esc_html__( 'Institution Type', 'yikes-level-playing-field' ),
				'callback' => $this->get_schooling_callback(),
				'options'  => [
					'High School'                       => esc_html__( 'High School', 'yikes-level-playing-field' ),
					'2-Year College'                    => esc_html__( '2-Year College', 'yikes-level-playing-field' ),
					'4-Year College'                    => esc_html__( '4-Year College', 'yikes-level-playing-field' ),
					'Trade/Technical/Vocational School' => esc_html__( 'Trade/Technical/Vocational School', 'yikes-level-playing-field' ),
					'Graduate School'                   => esc_html__( 'Graduate School', 'yikes-level-playing-field' ),
				],
			],
			ApplicantMeta::YEAR        => [
				'label' => esc_html__( 'Graduation Year', 'yikes-level-playing-field' ),
				'class' => Types::YEAR,
			],
			ApplicantMeta::MAJOR       => [
				'label' => esc_html__( 'Major', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::DEGREE      => [
				'label' => esc_html__( 'Degree', 'yikes-level-playing-field' ),
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
			'<legend class="lpf-field-schooling lpf-input-label">%s</legend>',
			esc_html__( 'Schooling:', 'yikes-level-playing-field' )
		);
	}

	/**
	 * Get a callback for generating a new Schooling field.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_add_new_label() {
		return esc_html_x( 'School', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
