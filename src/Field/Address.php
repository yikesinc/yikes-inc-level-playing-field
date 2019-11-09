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
 * Class Address
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Address extends ComplexField {

	/** @var string */
	protected $class_base = 'address';

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
		 * Filter the default address fields.
		 *
		 * @param array $fields Array of address fields.
		 */
		return apply_filters( 'lpf_field_address_fields', [
			ApplicantMeta::LINE_1  => [
				'label' => esc_html__( 'Line 1', 'level-playing-field' ),
			],
			ApplicantMeta::LINE_2  => [
				'label'    => esc_html__( 'Line 2', 'level-playing-field' ),
				'required' => false,
			],
			ApplicantMeta::CITY    => [
				'label' => esc_html__( 'City', 'level-playing-field' ),
			],
			ApplicantMeta::STATE   => [
				'label' => esc_html__( 'State', 'level-playing-field' ),
			],
			ApplicantMeta::COUNTRY => [
				'label' => esc_html__( 'Country', 'level-playing-field' ),
			],
			ApplicantMeta::ZIP     => [
				'label' => esc_html__( 'Postal Code', 'level-playing-field' ),
				'class' => Types::POSTAL_CODE,
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
			'<legend class="lpf-field-address lpf-input-label">%s</legend>',
			esc_html__( 'Address: ', 'level-playing-field' )
		);
	}
}
