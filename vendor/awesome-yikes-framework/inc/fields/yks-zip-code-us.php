<?php
/**** Zip Code U.S. (5-4) ****/

/**
* 
* This is a zip code field with two input fields - one with a maxlength of 5, the other with a maxlength of 4
*
* The other zip code field (yks-zip-code.php) is a simple input with no validation/constraints
*
*/

// Setup our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html		 = '';
$field_repeating = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
$field_counter	 = 0;
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-zip-code-us', YKS_MBOX_URL . 'js/fields/min/yks-zip-code-us.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_zip_code_us_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_counter++;

		$zip5_field_value = isset( $value['zip5'] ) ? $value['zip5'] : '';
		$zip4_field_value = isset( $value['zip4'] ) ? $value['zip4'] : '';

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_zip_code_us_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_zip_code_us yks_zip_code5" name="' . esc_attr( $field_id ) . '[zip5_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_zip5_' . $field_counter . '" value="' . $zip5_field_value . '" maxlength="5">';
		$field_html .= '<span class="yks_zip_code_us_dash">&ndash;</span>';
		$field_html .= '<input type="text" class="yks_zip_code_us yks_zip_code4" name="' . esc_attr( $field_id ) . '[zip4_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_zip4_' . $field_counter . '" value="' . $zip4_field_value . '" maxlength="4">';
		$field_html .= '<span class="yks_zip_code_us_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_zip_code_us_add_container">';
	$field_html .= '<span class="yks_zip_code_us_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';
	$zip5_field_value = isset( $value['zip5'] ) ? $value['zip5'] : '';
	$zip4_field_value = isset( $value['zip4'] ) ? $value['zip4'] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="text" class="yks_zip_code_us yks_zip_code5" name="' . esc_attr( $field_id ) . '[zip5]" id="' . esc_attr( $field_id ) . '_zip5" value="' . $zip5_field_value . '" maxlength="5"">';
	$field_html .= '<span class="yks_zip_code_us_dash">&ndash;</span>';
	$field_html .= '<input type="text" class="yks_zip_code_us yks_zip_code4" name="' . esc_attr( $field_id ) . '[zip4]" id="' . esc_attr( $field_id ) . '_zip4" value="' . $zip4_field_value . '" maxlength="4">';
}

// Field description
if ( $desc_type === 'inline' ) {

	// If desc_type is inline, use a span
	$field_html .= '<span class="yks_mbox_description yks_mbox_description_inline">' . $field_desc . '</span>';
} elseif ( $desc_type === 'block' ) {

	// If desc_type is block, use a p
	$field_html .= '<p class="yks_mbox_description yks_mbox_description_block">' . $field_desc . '</p>';
}

// Display our field on the page
echo $field_html;
return;
