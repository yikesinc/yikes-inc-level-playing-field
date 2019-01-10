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
 * @since   %VERSION%
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
			ApplicantMeta::ORGANIZATION => [
				'label' => esc_html__( 'Organization', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::INDUSTRY     => [
				'label' => esc_html__( 'Industry', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::START_DATE   => [
				'label' => esc_html__( 'Start Date', 'yikes-level-playing-field' ),
				'class' => Types::DATE,
			],
			ApplicantMeta::END_DATE     => [
				'label' => esc_html__( 'End Date', 'yikes-level-playing-field' ),
				'class' => Types::DATE,
			],
			ApplicantMeta::POSITION     => [
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
		printf(
			'<legend class="lpf-field-volunteer lpf-input-label">%s</legend>',
			esc_html__( 'Volunteer:', 'yikes-level-playing-field' )
		);
	}

	/**
	 * Get the label to use when rendering the "Add New" button.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_add_new_label() {
		return esc_html_x( 'Volunteer', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
