<?php
/**** DateTime MySQL Field ****/

/**
*
* Save as MySQL date & time (e.g. 2017-02-20 06:00:00)
* Displayed as (1) 'mm/dd/yyyy' e.g. 02/20/2017 and (2) 'h:i A' e.g. 06:00 PM
* Uses jQuery datepicker
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
	wp_enqueue_script( 'yks-text-datetime-mysql', YKS_MBOX_URL . 'js/fields/min/yks-text-datetime-mysql.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_txt_datetime_mysql_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$date_value = ! empty( $value ) ? date( 'm\/d\/Y', strtotime( $value ) ) : '';
		$time_value = ! empty( $value ) ? date( 'h:i A', strtotime( $value ) ) : '';

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_txt_datetime_mysql_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<input type="text" class="yks_date_pick yks_txt_datetime_mysql" name="' . esc_attr( $field_id ) . '[date_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_date_' . $field_counter . '" value="' . htmlspecialchars( $date_value ) . '" />';
		$field_html .= '<input type="text" class="yks_time_pick yks_txt_datetime_mysql" name="' . esc_attr( $field_id ) . '[time_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_time_' . $field_counter . '" value="' . htmlspecialchars( $time_value ) . '" />';
		$field_html .= '<span class="yks_txt_datetime_mysql_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_txt_datetime_mysql_add_container">';
	$field_html .= '<span class="yks_txt_datetime_mysql_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';
	$date_value = ! empty( $value ) ? date( 'm\/d\/Y', strtotime( $value ) ) : '';
	$time_value = ! empty( $value ) ? date( 'h:i A', strtotime( $value ) ) : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input type="text" class="yks_date_pick yks_txt_datetime_mysql" name="' . esc_attr( $field_id ) . '[date]" id="' . esc_attr( $field_id ) . '_date" value="' . htmlspecialchars( $date_value ) . '" />';
	$field_html .= '<input type="text" class="yks_time_pick yks_txt_datetime_mysql" name="' . esc_attr( $field_id ) . '[time]" id="' . esc_attr( $field_id ) . '_time" value="' . htmlspecialchars( $time_value ) . '" />';
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
