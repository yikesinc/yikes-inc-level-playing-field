<?php
/**
 * Address Field.
 *
 * Fields included: Address, Address 2, City, State, Province, Country, Postal Code.
 * States dropdown comes from the function yks_awesome_framework_states_array()
 * To Do: convert the countries to use the countries dropdown (w/ the function yks_awesome_framework_countries_array() ).
 *
 * @package YIKES Awesome Framework
 */

// Setup our defaults.
$field_values    = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html      = '';
$field_repeating = isset( $field['repeating'] ) && true === $field['repeating'];
$field_counter   = 0;
$field_id        = isset( $field['id'] ) ? $field['id'] : '';
$field_desc      = isset( $field['desc'] ) ? $field['desc'] : '';
$desc_type       = isset( $field['desc_type'] ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : __( 'Add A Field', 'yikes-level-playing-field' );

// States array.
$states = yks_awesome_framework_states_array();

// We store the address values as an array of arrays, so make sure we're dealing with an array of arrays.
$field_values = isset( $field_values[0] ) && is_array( $field_values[0] ) ? $field_values : array( $field_values );

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields.
	wp_enqueue_script( 'yks-address', YKS_MBOX_URL . 'js/fields/min/yks-address.min.js', array( 'jquery' ), YIKES_Awesome_Framework_Version, true );

	// Container for repeating fields.
	$field_html .= '<ul class="yks_address_container" id="container_' . esc_attr( $field_id ) . '">';

	// Loop through the fields.
	foreach ( $field_values as $value ) {

		// Defaults.
		$address1    = isset( $value['address-1'] ) ? $value['address-1'] : '';
		$address2    = isset( $value['address-2'] ) ? $value['address-2'] : '';
		$city        = isset( $value['city'] ) ? $value['city'] : '';
		$state       = isset( $value['state'] ) ? $value['state'] : '';
		$province    = isset( $value['province'] ) ? $value['province'] : '';
		$country     = isset( $value['country'] ) ? $value['country'] : '';
		$postal_code = isset( $value['zip'] ) ? $value['zip'] : '';

		// Check for 'provence' for legacy reasons.
		$province = isset( $value['provence'] ) ? $value['provence'] : $province;

		$field_counter++;

		// We hide the delete button for the first field.
		$style = 1 === $field_counter ? 'display: none;' : '';

		// <li>, input field, delete button.
		$field_html .= '<li class="yks_address_field ui-state-default">';
		$field_html .= '<span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<span class="yks_address_delete yks_repeating_delete_top dashicons dashicons-dismiss" style="' . esc_attr( $style ) . '"></span>';

		// Address 1.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_address-1_' . esc_attr( $field_counter ) . '">' . esc_html__( 'Address', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[address-1_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_address-1_' . esc_attr( $field_counter ) . '" value="' . htmlspecialchars( $address1 ) . '" />';

		// Address 2.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_address-2_' . esc_attr( $field_counter ) . '">' . esc_html__( 'Address 2', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[address-2_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_address-2_' . esc_attr( $field_counter ) . '" value="' . htmlspecialchars( $address2 ) . '" />';

		// City.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_city_' . esc_attr( $field_counter ) . '">' . esc_html__( 'City', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[city_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_city_' . $field_counter . '" value="' . htmlspecialchars( $city ) . '" />';

		// State.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_state_' . esc_attr( $field_counter ) . '">' . esc_html__( 'State', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<select class="yks_address" name="' . esc_attr( $field_id ) . '[state_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_state_' . esc_attr( $field_counter ) . '">';
		$field_html .= '<option value="">' . esc_html__( 'Select State', 'yikes-level-playing-field' ) . '</option>';
		foreach ( $states as $option ) {
			$option_value = isset( $option['value'] ) ? $option['value'] : '';
			$option_name  = isset( $option['name'] ) ? $option['name'] : '';
			$selected     = $state === $option_value ? 'selected="selected"' : '';

			$field_html .= '<option value="' . esc_attr( $option_value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $option_name ) . '</option>';
		}
		$field_html .= '</select>';

		// Province.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_province_' . esc_attr( $field_counter ) . '">' . esc_html__( 'Province', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[province_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_province_' . esc_attr( $field_counter ) . '" value="' . htmlspecialchars( $province ) . '" />';

		// Country.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_country_' . esc_attr( $field_counter ) . '">' . esc_html__( 'Country', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[country_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_country_' . esc_attr( $field_counter ) . '" value="' . htmlspecialchars( $country ) . '" />';

		// Postal Code.
		$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_zip_' . esc_attr( $field_counter ) . '">' . esc_html__( 'Postal Code', 'yikes-level-playing-field' ) . '</label></p>';
		$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[zip_' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_zip_' . esc_attr( $field_counter ) . '" value="' . htmlspecialchars( $postal_code ) . '" />';

		$field_html .= '</li>';
	}

	// Close container.
	$field_html .= '</ul>';

	// Add field button.
	$field_html .= '<div class="yks_address_add_container">';
	$field_html .= '<span class="yks_address_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . esc_html( $repeat_btn_text ) . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value.
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	// Defaults.
	$address1    = isset( $value['address-1'] ) ? $value['address-1'] : '';
	$address2    = isset( $value['address-2'] ) ? $value['address-2'] : '';
	$city        = isset( $value['city'] ) ? $value['city'] : '';
	$state       = isset( $value['state'] ) ? $value['state'] : '';
	$province    = isset( $value['province'] ) ? $value['province'] : '';
	$country     = isset( $value['country'] ) ? $value['country'] : '';
	$postal_code = isset( $value['zip'] ) ? $value['zip'] : '';

	// Check for 'provence' for legacy reasons.
	$province = isset( $value['provence'] ) ? $value['provence'] : $province;

	/**
	 * Not a repeating field - just add the input fields.
	 * Note: the _1 at the end of the `name` attribute is required for saving (and keeps consistency between repeating and non-repeating fields).
	 */

	// Address 1.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_address-1">' . esc_html__( 'Address', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[address-1_1]" id="' . esc_attr( $field_id ) . '_address-1" value="' . htmlspecialchars( $address1 ) . '" />';

	// Address 2.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_address-2">' . esc_html__( 'Address 2', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[address-2_1]" id="' . esc_attr( $field_id ) . '_address-2" value="' . htmlspecialchars( $address2 ) . '" />';

	// City.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_city">' . esc_html__( 'City', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[city_1]" id="' . esc_attr( $field_id ) . '_city" value="' . htmlspecialchars( $city ) . '" />';

	// State.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_state">' . esc_html__( 'State', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<select class="yks_address" name="' . esc_attr( $field_id ) . '[state_1]" id="' . esc_attr( $field_id ) . '_state">';
	$field_html .= '<option value="">' . esc_html__( 'Select State', 'yikes-level-playing-field' ) . '</option>';
	foreach ( $states as $option ) {
		$option_value = isset( $option['value'] ) ? $option['value'] : '';
		$option_name  = isset( $option['name'] ) ? $option['name'] : '';
		$selected     = $state === $option_value ? 'selected="selected"' : '';

		$field_html .= '<option value="' . esc_attr( $option_value ) . '"' . esc_attr( $selected ) . '>' . esc_html( $option_name ) . '</option>';
	}
	$field_html .= '</select>';

	// Province.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_province">' . esc_html__( 'Province', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[province_1]" id="' . esc_attr( $field_id ) . '_province" value="' . htmlspecialchars( $province ) . '" />';

	// Country.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_country">' . esc_html__( 'Country', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[country_1]" id="' . esc_attr( $field_id ) . '_country" value="' . htmlspecialchars( $country ) . '" />';

	// Postal Code.
	$field_html .= '<p><label for="' . esc_attr( $field_id ) . '_zip">' . esc_html__( 'Postal Code', 'yikes-level-playing-field' ) . '</label></p>';
	$field_html .= '<input type="text" class="yks_address" name="' . esc_attr( $field_id ) . '[zip_1]" id="' . esc_attr( $field_id ) . '_zip" value="' . htmlspecialchars( $postal_code ) . '" />';
}

// Field description.
if ( $desc_type === 'inline' ) {

	// If desc_type is inline, use a span.
	$field_html .= '<span class="yks_mbox_description yks_mbox_description_inline">' . esc_html( $field_desc ) . '</span>';
} elseif ( $desc_type === 'block' ) {

	// If desc_type is block, use a p.
	$field_html .= '<p class="yks_mbox_description yks_mbox_description_block">' . esc_html( $field_desc ) . '</p>';
}

// Display our field on the page.
echo $field_html; // phpcs:ignore WordPress.Security.EscapeOutput
return;
