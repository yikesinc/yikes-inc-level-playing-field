<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Exception\InvalidField;

/**
 * Class Address
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Address extends BaseField {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 *
	 * @throws InvalidField When an invalid field class is provided through the filter.
	 */
	public function render() {
		$classes = array_merge( $this->classes, [ 'lpf-field-address' ] );

		/**
		 * Filter the default address fields.
		 *
		 * @param array $fields Array of address fields.
		 */
		$default_fields = apply_filters( 'lpf_field_address_fields', [
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

		// Generate the sub-fields for the address.
		$sub_fields = [];
		foreach ( $default_fields as $field => $settings ) {
			$settings = wp_parse_args( $settings, [
				'label'    => ucwords( str_replace( [ '_', '-' ], ' ', $field ) ),
				'class'    => Types::TEXT,
				'required' => true,
			] );

			$sub_fields[] = new $settings['class'](
				"{$this->id}[{$field}]",
				$settings['label'],
				$classes,
				(bool) $settings['required']
			);

			// Ensure the class extends the Field interface.
			if ( ! ( end( $sub_fields ) instanceof Field ) ) {
				throw InvalidField::from_field( $settings['class'] );
			}
		}

		// Render the grouping label.
		printf(
			'<label class="lpf-field-address lpf-input-label">%s</label>',
			esc_html__( 'Address: ', 'yikes-level-playing-field' )
		);

		/** @var Field $sub_field */
		foreach ( $sub_fields as $sub_field ) {
			$sub_field->render();
		}
	}
}
