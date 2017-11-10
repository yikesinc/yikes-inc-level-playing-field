<?php
/**** Text Phone Number ****/

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
	wp_enqueue_script( 'yks-text-phone-number-desc-value', YKS_MBOX_URL . 'js/fields/min/yks-text-phone-number-desc-value.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_phone_number_desc_value_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_name	= isset( $value['name'] ) ? $value['name'] : '';
		$field_phone	= isset( $value['phone'] ) ? $value['phone'] : '';
		$field_phone1	= ! empty( $field_phone ) && isset( $field_phone[0] ) ? $field_phone[0] : '';
		$field_phone2	= ! empty( $field_phone ) && isset( $field_phone[1] ) ? $field_phone[1] : '';
		$field_phone3	= ! empty( $field_phone ) && isset( $field_phone[2] ) ? $field_phone[2] : '';

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_phone_number_desc_value_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_txt_medium yks_phone_number_desc_value" name="' . esc_attr( $field_id ) . '[name_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_' . $field_counter . '" value="' . htmlspecialchars( $field_name ) . '" />';
		$field_html .= '(<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_1 yks_phone_number_desc_value" maxlength="3" name="' . esc_attr( $field_id ) . '[one_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_one_' . $field_counter . '" value="' . htmlspecialchars( $field_phone1 ) . '" data-phone-field-num="1" />)';
		$field_html .= '<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_2 yks_phone_number_desc_value" maxlength="3" name="' . esc_attr( $field_id ) . '[two_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_two_' . $field_counter . '" value="' . htmlspecialchars( $field_phone2 ) . '" data-phone-field-num="2" />';
		$field_html .= '<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_3 yks_phone_number_desc_value" maxlength="4" name="' . esc_attr( $field_id ) . '[three_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_three_' . $field_counter . '" value="' . htmlspecialchars( $field_phone3 ) . '" data-phone-field-num="3" />';
		$field_html .= '<span class="yks_phone_number_desc_value_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_phone_number_desc_value_add_container">';
	$field_html .= '<span class="yks_phone_number_desc_value_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';
	$field_name	= isset( $value['name'] ) ? $value['name'] : '';
	$field_phone	= isset( $value['phone'] ) ? $value['phone'] : '';
	$field_phone1	= ! empty( $field_phone ) && isset( $field_phone[0] ) ? $field_phone[0] : '';
	$field_phone2	= ! empty( $field_phone ) && isset( $field_phone[1] ) ? $field_phone[1] : '';
	$field_phone3	= ! empty( $field_phone ) && isset( $field_phone[2] ) ? $field_phone[2] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="text" class="yks_txt_medium yks_phone_number_desc_value" name="' . esc_attr( $field_id ) . '[name]" id="' . esc_attr( $field_id ) . '_text" value="' . htmlspecialchars( $field_name ) . '" />';
	$field_html .= '<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_1 yks_phone_number_desc_value" name="' . esc_attr( $field_id ) . '[one]" id="' . esc_attr( $field_id ) . '_one" value="' . htmlspecialchars( $field_phone1 ) . '" maxlength="3" data-phone-field-num="1" />';
	$field_html .= '<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_2 yks_phone_number_desc_value" name="' . esc_attr( $field_id ) . '[two]" id="' . esc_attr( $field_id ) . '_two" value="' . htmlspecialchars( $field_phone2 ) . '" maxlength="3" data-phone-field-num="2" />';
	$field_html .= '<input type="text" class="yks_txt_small yks_phone_number yks_phone_number_3 yks_phone_number_desc_value" name="' . esc_attr( $field_id ) . '[three]" id="' . esc_attr( $field_id ) . '_three" value="' . htmlspecialchars( $field_phone3 ) . '" maxlength="4" data-phone-field-num="3" />';
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
