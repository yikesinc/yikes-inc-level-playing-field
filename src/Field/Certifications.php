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
 * @since   1.0.0
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
	 * @since 1.0.0
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
				'label' => esc_html__( 'Institution', 'level-playing-field' ),
			],
			ApplicantMeta::TYPE        => [
				'label' => esc_html__( 'Institution Type', 'level-playing-field' ),
			],
			ApplicantMeta::YEAR        => [
				'label' => esc_html__( 'Year Certified', 'level-playing-field' ),
				'class' => Types::YEAR,
			],
			ApplicantMeta::CERT_TYPE   => [
				'label' => esc_html__( 'Certification Type', 'level-playing-field' ),
			],
			ApplicantMeta::STATUS      => [
				'label' => esc_html__( 'Status', 'level-playing-field' ),
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
			'<legend class="lpf-field-certifications lpf-input-label">%s</legend>',
			esc_html__( 'Certifications:', 'level-playing-field' )
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
			'<div class="lpf-field-certifications lpf-fieldset-label">%1$s <span class="lpf-fieldset-number">%2$s</span></div>',
			esc_html__( 'Certification', 'level-playing-field' ),
			esc_html__( '1', 'level-playing-field' )
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
		return esc_html_x( 'Certification', 'for "add new" button', 'level-playing-field' );
	}
}
