<?php
/**** End Text Time Formatted ****/

/**
*
* NOTE: The only difference between this field and "yks-text-time-formatted" is that this field allows for an empty time
* (i.e., the first <option> in each dropdown is '' instead of 12 00 AM)
*
* Time field - dropdown for hour, minutes, AM/PM
*
* All three dropdowns (hour, minute, am/pm) must be filled out in order for the values to be saved
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

// Setup our time arrays for our dropdowns
$hours_array	= array( '00' => '12', '11' => '11', '10' => '10', '09' => '09', '08' => '08', '07' => '07', '06' => '06', '05' => '05', '04' => '04', '03' => '03', '02' => '02', '01' => '01' );
$minutes_array	= array( '00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55' );
$am_pm_array	= array( '1' => 'AM', '2' => 'PM' );

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-end-text-time-formatted', YKS_MBOX_URL . 'js/fields/min/yks-end-text-time-formatted.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_end_txt_time_formatted_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		// Translate 24 hour string (e.g. 1815) into 12-hour hour, minute, am/pm (e.g. 1815 -> 6 15 PM )
		$hour	= yks_mbox_text_time_formatted_get_hour( $value );
		$minute	= yks_mbox_text_time_formatted_get_minute( $value );
		$ampm	= yks_mbox_text_time_formatted_get_am_pm( $value, $default_empty = true );

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_end_txt_time_formatted_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';

		// Hours dropdown
		$field_html  .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[hour_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_hour_' . $field_counter . '">';
		$field_html  	.= '<option value=""></option>';
		foreach ( $hours_array as $value => $display ) {
			$selected = ( (string) $hour === (string) $value ) ? 'selected' : '';
			$field_html .= '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $display . '</option>';
		}
		$field_html  .= '</select>';

		// Colon separator
		$field_html  .= '<span class="yks_end_txt_time_formatted_colon"> : </span>';

		// Minutes dropdown
		$field_html  .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[minute_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_minute_' . $field_counter . '">';
		$field_html  	.= '<option value=""></option>';
		foreach ( $minutes_array as $minutes ) {

			// Force the comparison as a string, or else the 00 will be the default because `(int) ''` = 0, and `(int) 00` = 0
			$selected = ( (string) $minute === (string) $minutes ) ? 'selected' : '';
			$field_html .= '<option value="' . esc_attr( $minutes ) . '"' . $selected . '>' . $minutes . '</option>';
		}
		$field_html  .= '</select>';

		// Span separator
		$field_html  .= '<span> </span>';

		// AM/PM dropdown
		$field_html  .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[ampm_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_ampm_' . $field_counter . '">';
		$field_html  	.= '<option value=""></option>';
		foreach ( $am_pm_array as $value => $display ) {
			$selected = ( (string) $ampm === (string) $value ) ? 'selected' : '';
			$field_html .= '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $display . '</option>';
		}
		$field_html  .= '</select>';

		$field_html  .= '<span class="yks_end_txt_time_formatted_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_end_txt_time_formatted_add_container">';
	$field_html  .= '<span class="yks_end_txt_time_formatted_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value	= isset( $field_values[0] ) ? $field_values[0] : '';

	// Translate 24 hour string (e.g. 1815) into 12-hour hour, minute, am/pm (e.g. 1815 -> 6 15 PM )
	$hour	= yks_mbox_text_time_formatted_get_hour( $value );
	$minute	= yks_mbox_text_time_formatted_get_minute( $value );
	$ampm	= yks_mbox_text_time_formatted_get_am_pm( $value, $default_empty = true );

	// Hours dropdown
	$field_html .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[hour]" id="' . esc_attr( $field_id ) . '_hour">';
	$field_html 	.= '<option value=""></option>';
	foreach ( $hours_array as $value => $display ) {
		$selected = ( (string) $hour === (string) $value ) ? 'selected' : '';
		$field_html .= '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $display . '</option>';
	}
	$field_html .= '</select>';

	// Colon separator
	$field_html .= '<span class="yks_end_txt_time_formatted_colon"> : </span>';

	// Minutes dropdown
	$field_html .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[minute]" id="' . esc_attr( $field_id ) . '_minute">';
	$field_html 	.= '<option value=""></option>';
	foreach ( $minutes_array as $minutes ) {

		// Force the comparison as a string, or else the 00 will be the default because (int) '' === (int) 00
		$selected = ( (string) $minute === (string) $minutes ) ? 'selected' : '';
		$field_html .= '<option value="' . esc_attr( $minutes ) . '"' . $selected . '>' . $minutes . '</option>';
	}
	$field_html .= '</select>';

	// Span separator
	$field_html .= '<span> </span>';

	// AM/PM dropdown
	$field_html .= '<select class="yks_end_txt_time_formatted" name="' . esc_attr( $field_id ) . '[ampm]" id="' . esc_attr( $field_id ) . '_ampm">';
	$field_html 	.= '<option value=""></option>';
	foreach ( $am_pm_array as $value => $display ) {
		$selected = ( (string) $ampm === (string) $value ) ? 'selected' : '';
		$field_html .= '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $display . '</option>';
	}
	$field_html .= '</select>';
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
