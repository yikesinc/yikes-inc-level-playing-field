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
 * Class Experience
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Experience extends ComplexField {

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
		return [ 'lpf-field-experience' ];
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
		 * Filter the default experience fields.
		 *
		 * @param array $fields Array of experience fields.
		 */
		return apply_filters( 'lpf_field_experience_fields', [
			'organization' => [
				'label' => esc_html__( 'Organization', 'yikes-level-playing-field' ),
			],
			'industry'     => [
				'label' => esc_html__( 'Industry', 'yikes-level-playing-field' ),
			],
			'start_date'   => [
				'label' => esc_html__( 'Start Date', 'yikes-level-playing-field' ),
				'class' => Types::DATE,
			],
			'end_date'     => [
				'label' => esc_html__( 'End Date', 'yikes-level-playing-field' ),
				'class' => Types::DATE,
			],
			'position'     => [
				'label' => esc_html__( 'Position', 'yikes-level-playing-field' ),
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
