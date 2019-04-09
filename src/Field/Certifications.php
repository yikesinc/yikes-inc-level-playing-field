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
 * Class Certifications
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Certifications extends RepeatableField {

	/** @var string */
	protected $class_base = 'certifications';

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
			ApplicantMeta::INSTITUTION => [
				'label' => esc_html__( 'Institution', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::TYPE        => [
				'label' => esc_html__( 'Institution Type', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::YEAR        => [
				'label' => esc_html__( 'Year Certified', 'yikes-level-playing-field' ),
				'class' => Types::YEAR,
			],
			ApplicantMeta::CERT_TYPE   => [
				'label' => esc_html__( 'Certification Type', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::STATUS      => [
				'label' => esc_html__( 'Status', 'yikes-level-playing-field' ),
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
			esc_html__( 'Certifications:', 'yikes-level-playing-field' )
		);
	}

	/**
	 * Render the label for the repeatable fields.
	 *
	 * This should echo the label directly.
	 *
	 * @since %VERSION%
	 */
	protected function render_repeatable_field_label() {
		printf(
			'<p class="lpf-field-certifications lpf-fieldset-label">%1$s <span class="lpf-fieldset-number">%2$s</span></p>',
			esc_html__( 'Certification', 'yikes-level-playing-field' ),
			esc_html__( '1', 'yikes-level-playing-field' )
		);
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
		return esc_html_x( 'Certification', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
