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
class Experience extends RepeatableField {

	/** @var string */
	protected $class_base = 'experience';

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
		printf(
			'<legend class="lpf-field-experience lpf-input-label">%s</legend>',
			esc_html__( 'Experience:', 'yikes-level-playing-field' )
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
		return esc_html_x( 'Experience', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
