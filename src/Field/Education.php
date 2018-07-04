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
 * Class Education
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Education extends ComplexField {

	/**
	 * Whether the field is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $repeatable = true;

	/**
	 * Get the array of classes to merge in with the default field classes.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_classes() {
		return [ 'lpf-field-education' ];
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
	protected function get_default_fields() {
		/**
		 * Filter the default education fields.
		 *
		 * @param array $fields Array of education fields.
		 */
		return apply_filters( 'lpf_field_education_fields', [
			'institution'      => [
				'label' => esc_html__( 'Institution' ),
			],
			'institution-type' => [
				'label' => esc_html__( 'Institution Type' ),
			],
			'year'             => [
				'label' => esc_html__( 'Graduation Year' ),
				'class' => Types::YEAR,
			],
			'major'            => [
				'label' => esc_html__( 'Major' ),
			],
			'degree'           => [
				'label' => esc_html__( 'Degree' ),
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
		// TODO: Implement render_grouping_label() method.
	}
}
