<?php
/**** Questions Field ****/

/**
*
* Questions - an input field and checkbox
* The checkbox determines if the input field is required
* Most notably used in Events Registration where we add these questions to the registration form
*
* This field is setup to use desc_type inline/block - but keep it block.
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
	wp_enqueue_script( 'yks-questions', YKS_MBOX_URL . 'js/fields/min/yks-questions.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_questions_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		// Defaults
		$value_label = isset( $value['label'] ) ? $value['label'] : '';
		$req_checked = ( isset( $value['required'] ) && ! empty( $value['required'] ) ) ? 'checked="checked"' : '';

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_questions_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_questions yks_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[label_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_label_' . $field_counter . '" value="' . htmlspecialchars( $value_label ) . '" />';
		$field_html .= '<input type="checkbox" class="yks_questions yks_questions_required" name="' . esc_attr( $field_id ) . '[required_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_required_' . $field_counter . '" value="1" ' . $req_checked . ' / title="Required">';
		$field_html .= '<span class="yks_questions_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_questions_add_container">';
	$field_html .= '<span class="yks_questions_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	// Defaults
	$value_label = isset( $value['label'] ) ? $value['label'] : '';
	$req_checked = ( isset( $value['required'] ) && ! empty( $value['required'] ) ) ? 'checked="checked"' : '';

	// Not a repeating field - just add the input fields
	$field_html .= '<input type="text" class="yks_questions yks_non_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[label_1]" id="' . esc_attr( $field_id ) . '_label_1" value="' . htmlspecialchars( $value_label ) . '"/>';
	$field_html .= '<input type="checkbox" class="yks_questions yks_questions_required" name="' . esc_attr( $field_id ) . '[required_1]" id="' . esc_attr( $field_id ) . '_required_1" value="1" ' . $req_checked . '/>';
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
