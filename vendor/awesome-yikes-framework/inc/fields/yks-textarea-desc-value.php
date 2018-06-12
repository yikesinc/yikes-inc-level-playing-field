<?php
/**** Textarea Desc/Value ****/

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
	wp_enqueue_script( 'yks-textarea-desc-value', YKS_MBOX_URL . 'js/fields/min/yks-textarea-desc-value.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_textarea_desc_value_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_counter++;
		$desc_field_value	= isset( $value['desc'] ) ? $value['desc'] : '';
		$val_field_value	= isset( $value['val'] ) ? $value['val'] : '';

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_textarea_desc_value_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_textarea_desc_value yks_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[desc_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_desc_' . $field_counter . '" value="' . $desc_field_value . '">';
		$field_html .= '<textarea class="yks_textarea_desc_value yks_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[val_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_val_' . $field_counter . '" cols="60" rows="4">' . $val_field_value . '</textarea>';
		$field_html .= '<span class="yks_textarea_desc_value_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_textarea_desc_value_add_container">';
	$field_html .= '<span class="yks_textarea_desc_value_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';
	$desc_field_value	= isset( $value['desc'] ) ? $value['desc'] : '';
	$val_field_value	= isset( $value['val'] ) ? $value['val'] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="text" class="yks_textarea_desc_value yks_non_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[desc]" id="' . esc_attr( $field_id ) . '_desc" value="' . $desc_field_value . '">';
	$field_html .= '<textarea class="yks_textarea_desc_value" name="' . esc_attr( $field_id ) . '[val]" id="' . esc_attr( $field_id ) . '_val" cols="60" rows="7">';
	$field_html .= $val_field_value;
	$field_html .= '</textarea>';
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
