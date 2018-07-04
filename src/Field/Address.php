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
 * Class Address
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Address extends ComplexField {

	/**
	 * Get the array of classes to merge in with the default field classes.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_classes() {
		return [ 'lpf-field-address' ];
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
		 * Filter the default address fields.
		 *
		 * @param array $fields Array of address fields.
		 */
		return apply_filters( 'lpf_field_address_fields', [
			'address-1' => [
				'label' => esc_html__( 'Line 1', 'yikes-level-playing-field' ),
			],
			'address-2' => [
				'label'    => esc_html__( 'Line 2', 'yikes-level-playing-field' ),
				'required' => false,
			],
			'city'      => [
				'label' => esc_html__( 'City', 'yikes-level-playing-field' ),
			],
			'state'     => [
				'label' => esc_html__( 'State', 'yikes-level-playing-field' ),
			],
			'country'   => [
				'label' => esc_html__( 'Country', 'yikes-level-playing-field' ),
			],
			'zip'       => [
				'label' => esc_html__( 'Postal Code', 'yikes-level-playing-field' ),
				'class' => Types::POSTAL_CODE,
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
			'<label class="lpf-field-address lpf-input-label">%s</label>',
			esc_html__( 'Address: ', 'yikes-level-playing-field' )
		);
	}
}
