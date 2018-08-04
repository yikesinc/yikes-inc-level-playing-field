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
 * Class Certifications
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Certifications extends ComplexField {

	/** @var string */
	protected $class_base = 'certifications';

	/**
	 * Whether the field is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $repeatable = true;

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
	 * Render the grouping label for the sub-fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since %VERSION%
	 */
	protected function render_grouping_label() {
		printf(
			'<legend class="lpf-field-certifications lpf-input-label">%s</legend>',
			esc_html__( 'Certifications: ', 'yikes-level-playing-field' )
		);
	}
}
