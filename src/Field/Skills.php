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
use Yikes\LevelPlayingField\Model\ApplicantMetaDropdowns;

/**
 * Class Skills
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Skills extends RepeatableField {

	use ApplicantMetaDropdowns;

	/** @var string */
	protected $class_base = 'skills';

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
			ApplicantMeta::SKILL       => [
				'label' => esc_html__( 'Skill', 'yikes-level-playing-field' ),
			],
			ApplicantMeta::PROFICIENCY => [
				'label'    => esc_html__( 'Proficiency', 'yikes-level-playing-field' ),
				'callback' => $this->get_skills_callback(),
				'options'  => $this->get_skills_options(),
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
	 * Get a callback for generating a new Schooling field.
	 *
	 * @since %VERSION%
	 * @return \Closure
	 */
	private function get_skills_callback() {
		return function( $id_base, $field, $classes, $settings ) {
			$options = [];
			foreach ( $settings['options'] as $value => $label ) {
				$options[] = new SelectOption( $label, $value );
			}

			return new Select(
				"{$id_base}[{$field}]",
				$settings['label'],
				$classes,
				(bool) $settings['required'],
				$options
			);
		};
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
