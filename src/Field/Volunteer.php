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
 * Class Volunteer
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Volunteer extends RepeatableField {

	/** @var string */
	protected $class_base = 'volunteer';

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
		 * Filter the default volunteer fields.
		 *
		 * @param array $fields Array of fields for Volunteer data.
		 */
		return apply_filters( 'lpf_field_volunteer_fields', [
			ApplicantMeta::ORGANIZATION     => [
				'label' => esc_html__( 'Organization', 'level-playing-field' ),
			],
			ApplicantMeta::INDUSTRY         => [
				'label' => esc_html__( 'Industry', 'level-playing-field' ),
			],
			ApplicantMeta::START_DATE       => [
				'label'   => esc_html__( 'Start Date', 'level-playing-field' ),
				'class'   => Types::DATE,
				'classes' => [ 'lpf-datepicker' ],
			],
			ApplicantMeta::PRESENT_POSITION => [
				'label'    => esc_html__( 'Presently Volunteering', 'level-playing-field' ),
				'class'    => Types::CHECKBOX,
				'required' => false,
			],
			ApplicantMeta::END_DATE         => [
				'label'    => esc_html__( 'End Date', 'level-playing-field' ),
				'class'    => Types::DATE,
				'required' => false,
				'classes'  => [ 'lpf-datepicker' ],
			],
			ApplicantMeta::POSITION         => [
				'label' => esc_html__( 'Position', 'level-playing-field' ),
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
			'<legend class="lpf-field-volunteer lpf-input-label">%s</legend>',
			esc_html__( 'Volunteer:', 'level-playing-field' )
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
			'<div class="lpf-field-volunteer lpf-fieldset-label">%1$s <span class="lpf-fieldset-number">%2$s</span></div>',
			esc_html__( 'Volunteer', 'level-playing-field' ),
			esc_html__( '1', 'level-playing-field' )
		);
	}

	/**
	 * Get the label to use when rendering the "Add New" button.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_add_new_label() {
		return esc_html_x( 'Volunteer Position', 'for "add new" button', 'level-playing-field' );
	}
}
