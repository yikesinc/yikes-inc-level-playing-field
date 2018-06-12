<?php
/**** Three Text Fields ****/

// Setup our defaults
$field_values	     = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html		     = '';
$field_repeating     = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
$field_counter	     = 0;
$field_id		     = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		     = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		     = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$repeat_btn_text     = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';
$field_one_classes   = ( isset( $field['classes'] ) && isset( $field['classes'][0] ) ) ? $field['classes'][0] : array( 'yks_txt_small' );
$field_two_classes   = ( isset( $field['classes'] ) && isset( $field['classes'][1] ) ) ? $field['classes'][1] : $field_one_classes;
$field_three_classes = ( isset( $field['classes'] ) && isset( $field['classes'][2] ) ) ? $field['classes'][2] : $field_one_classes;

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-text-three-fields', YKS_MBOX_URL . 'js/fields/min/yks-text-three-fields.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_txt_three_fields_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_one_value	= isset( $value['one'] ) ? $value['one'] : '';
		$field_two_value	= isset( $value['two'] ) ? $value['two'] : '';
		$field_three_value	= isset( $value['three'] ) ? $value['three'] : '';

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_txt_three_fields_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_one_classes ) . '" name="' . esc_attr( $field_id ) . '[one_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_one_' . $field_counter . '" value="' . htmlspecialchars( $field_one_value ) . '" />';
		$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_two_classes ) . '" name="' . esc_attr( $field_id ) . '[two_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_two_' . $field_counter . '" value="' . htmlspecialchars( $field_two_value ) . '" />';
		$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_three_classes ) . '" name="' . esc_attr( $field_id ) . '[three_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_three_' . $field_counter . '" value="' . htmlspecialchars( $field_three_value ) . '" />';
		$field_html .= '<span class="yks_txt_three_fields_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_txt_three_fields_add_container">';
	$field_html .= '<span class="yks_txt_three_fields_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';
	$field_one_value	= isset( $value['one'] ) ? $value['one'] : '';
	$field_two_value	= isset( $value['two'] ) ? $value['two'] : '';
	$field_three_value	= isset( $value['three'] ) ? $value['three'] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_one_classes ) . '" name="' . esc_attr( $field_id ) . '[one]" id="' . esc_attr( $field_id ) . '_one" value="' . htmlspecialchars( $field_one_value ) . '" />';
	$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_two_classes ) . '" name="' . esc_attr( $field_id ) . '[two]" id="' . esc_attr( $field_id ) . '_two" value="' . htmlspecialchars( $field_two_value ) . '" />';
	$field_html .= '<input type="text" class="yks_txt_three_fields ' . implode( " ", $field_three_classes ) . '" name="' . esc_attr( $field_id ) . '[three]" id="' . esc_attr( $field_id ) . '_three" value="' . htmlspecialchars( $field_three_value ) . '" />';
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
