<?php
/**** Multiple Checkboxes ****/

/**
*
* Checkboxes - inline - with a label wrapped around them
* Options defined by the $field['options'] array
*
* Checkbox values stored as an array of arrays e.g.:
*	array(
		array( 'value1', 'value2' )
*	)
*
*/

// Setup our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_options	 = ( isset( $field['options'] ) ) ? $field['options'] : array();
$field_html		 = '';
$field_repeating = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
$field_counter	 = 0;
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';

// We store the checkbox values as an array of arrays, so make sure we're dealing with an array of arrays
$field_values	 = ( isset( $field_values[0] ) && is_array( $field_values[0] ) ) ? $field_values : array( $field_values );

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-multicheck', YKS_MBOX_URL . 'js/fields/min/yks-multicheck.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_multicheck_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $values ) {

		foreach ( $values as $value ) {

			$check_counter = 0;

			$field_counter++;

			// We hide the delete button for the first field
			$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

			// <li>, input field, delete button
			$field_html .= '<li class="yks_multicheck_field ui-state-default">';
			$field_html .= '<span data-code="f156" class="dashicons dashicons-sort" ></span>';

			foreach ( $field_options as $option_value => $option_name ) {
				$check_counter++;
				$checked = in_array( $option_value, $value ) ? 'checked="checked"' : '';

			 	$field_html .= '<label class="yks_multicheck_label" for="' . esc_attr( $field_id ) . '_' . $check_counter . '_' . $field_counter . '">';
				$field_html .= '<input type="checkbox" class="yks_multicheck" name="' . esc_attr( $field_id ) . '[_' . $field_counter . '][]" id="' . esc_attr( $field_id ) . '_' . $check_counter . '_' . $field_counter . '" value="' . esc_attr( $option_value ) . '"' . $checked . '/>';
				$field_html .= $option_name;
				$field_html .= '</label>';
			}

			$field_html .= '<span class="yks_multicheck_delete dashicons dashicons-dismiss" ' . $style . '></span>';
			$field_html .= '</li>';
		}
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_multicheck_add_container">';
	$field_html .= '<span class="yks_multicheck_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';

} else {
	$check_counter = 0;

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	foreach ( $field_options as $option_value => $option_name ) {
		$check_counter++;
		$checked = ( in_array( $option_value, $value ) ) ? 'checked="checked"' : '';

	 	$field_html .= '<label class="yks_multicheck_label" for="' . esc_attr( $field_id ) . $check_counter . '">';
		$field_html .= '<input type="checkbox" class="yks_multicheck" name="' . esc_attr( $field_id ) . '[]" id="' . esc_attr( $field_id ) . $check_counter . '" value="' . esc_attr( $option_value ) . '"' . $checked . '/>';
		$field_html .= $option_name;
		$field_html .= '</label>';
	}
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
