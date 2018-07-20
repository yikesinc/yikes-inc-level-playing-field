<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

class Volunteer extends ComplexField {

	/**
	 * Get the array of classes to merge in with the default field classes.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_classes() {
		return [ 'lpf-field-volunteer' ];
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
		 * Filter the default volunteer fields.
		 *
		 * @param array $fields Array of fields for Volunteer data.
		 */
		return apply_filters( 'lpf_field_volunteer_fields', [
			'organization' => [
				'label' => esc_html__( 'Organization' ),
			],
			'type'         => [
				'label' => esc_html__( 'Organization Type' ),
			],
			'start_date'   => [
				'label' => esc_html__( 'Start Date' ),
				'class' => Types::DATE,
			],
			'end_date'     => [
				'label' => esc_html__( 'End Date' ),
				'class' => Types::DATE,
			],
			'position'     => [
				'label' => esc_html__( 'Position' ),
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
