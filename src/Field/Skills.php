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
 * Class Skills
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Skills extends ComplexField {

	/** @var string */
	protected $class_base = 'skills';

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
		return apply_filters( 'lpf_field_skills_fields', [
			'skill'       => [
				'label' => esc_html__( 'Skill', 'yikes-level-playing-field' ),
			],
			'proficiency' => [
				'label' => esc_html__( 'Proficiency', 'yikes-level-playing-field' ),
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
			'<legend class="lpf-field-skills lpf-input-label">%s</legend>',
			esc_html__( 'Skills:', 'yikes-level-playing-field' )
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
		return esc_attr_x( 'Skill', 'for "add new" button', 'yikes-level-playing-field' );
	}
}
