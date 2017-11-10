<?php
/**** Single Checkbox ****/

/**
*
* A single checkbox with a label wrapped around it
*
* This field is setup to use 'repeating', but I'm not sure of a real world case for repeating => true
* Because checkboxes won't save a value if their not checked off, so repeating would only save X amount of checked checkboxes
*
* Important:
* To properly use this field remember to set a value of 1
* eg:
* array(
*    'name' => 'Sample Checkbox Field',
*    'desc' => 'This is a sample checkbox field.',
*    'id'   => $prefix . 'sample_checkbox',
*    'value' => 1,
*    'type' => 'checkbox',
*  'repeating' => false,
* )
*
*/

// Setup our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_value	 = ( isset( $field['value'] ) ) ? $field['value'] : 1;
$field_html		 = '';
$field_repeating = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
$field_counter	 = 0;
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-checkbox', YKS_MBOX_URL . 'js/fields/min/yks-checkbox.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_checkbox_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_checkbox_field ui-state-default">';
		$field_html  .= '<span data-code="f156" class="dashicons dashicons-sort" ></span>';

		$checked = ( (string) $value === (string) $field_value ) ? 'checked="checked"' : '';

		$field_html  .= '<input type="checkbox" class="yks_checkbox" name="' . esc_attr( $field_id ) . '[' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_' . $field_counter . '" value="' . esc_attr( $field_value ) . '"' . $checked . '/>';

		$field_html  .= '<span class="yks_checkbox_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_checkbox_add_container">';
	$field_html  .= '<span class="yks_checkbox_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';

} else {

	// Get the field value
	$value	 = isset( $field_values[0] ) ? $field_values[0] : '';
	$checked = ( (string) $value === (string) $field_value ) ? 'checked="checked"' : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="checkbox" class="yks_checkbox" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '"' . $checked . '/>';
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
