<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

class Certifications extends ComplexField {

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
		return [ 'lpf-field-certifications' ];
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
		 * Filter the default certification fields.
		 *
		 * @param array $fields Array of certification fields.
		 */
		return apply_filters( 'lpf_field_certification_fields', [
			'institution' => [
				'label' => esc_html__( 'Certifying Institution', 'yikes-level-playing-field' ),
			],
			'year'        => [
				'label' => esc_html__( 'Year Certified', 'yikes-level-playing-field' ),
				'class' => Types::YEAR,
			],
			'type'        => [
				'label' => esc_html__( 'Certification Type', 'yikes-level-playing-field' ),
			],
			'status'      => [
				'label' => esc_html__( 'Certification Status', 'yikes-level-playing-field' ),
			],
		] );
	}

	/**
	 * Render the sub-fields.
	 *
	 * @since %VERSION%
	 * @todo Control how many fields are rendered by default
	 */
	protected function render_sub_fields() {
		for ( $i = 0; $i < 3; $i ++ ) {
			parent::render_sub_fields();
		}
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
