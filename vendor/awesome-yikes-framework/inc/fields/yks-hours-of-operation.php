<?php
/**** Hours of Operation ****/

/**
*
* Hours of Operation Field
*
* No repeating logic
*
* For each day of the week, display two timepickers and a checkbox
* Save as an array for each day of the week, e.g.
*
*  [1]=>
*  array(2) {
*    ["monday_open"]=>
*    string(8) "08:00 AM"
*    ["monday_close"]=>
*    string(8) "08:30 AM"
*  }
*
* ...
*
*  [7]=>
*  array(3) {
*    ["sunday_open"]=>
*    string(8) "07:30 AM"
*    ["sunday_close"]=>
*    string(8) "09:30 AM"
*    ["sunday_closed_override"]=>
*    string(2) "on"
*  }
*
*/

// Enqueue our JS file
wp_enqueue_script( 'yks-hours-of-operation', YKS_MBOX_URL . 'js/fields/min/yks-hours-of-operation.min.js', array( 'jquery' ) );

// Set up our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$days_array		 = array( '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday' );

// Loop through the days of the week and create the HTML for each day
$field_html .= '<div class="yks_hours_of_operation_container">';

foreach ( $days_array as $i => $day ) {
	$stl_day		= strtolower( $day );
	$open_value		= isset( $field_values[ $i ][ $stl_day . '_open' ] ) ? $field_values[ $i ][ $stl_day . '_open' ] : '';
	$close_value	= isset( $field_values[ $i ][ $stl_day . '_close' ] ) ? $field_values[ $i ][ $stl_day . '_close' ] : '';
	$override_check = isset( $field_values[ $i ][ $stl_day . '_closed_override' ] ) ? 'checked="checked"' : '';
	$override_class = isset( $field_values[ $i ][ $stl_day . '_closed_override' ] ) ? 'disabled' : '';

	$field_html .= '<div class="yks_hours_of_operation_day_container">';
	$field_html .= '<span class="yks_hours_of_operation_day_label">' . $day . '</span>';
	$field_html .= '<input type="text" class="yks_time_pick yks_hours_of_operation yks_time_pick_custom_margin ' . $override_class . '" name="' . esc_attr( $field_id ) . '[' . $i . '][' . esc_attr( $stl_day ) . '_open]" id="' . esc_attr( $field_id ) . '_' . esc_attr( $stl_day ) . '_open" value="' . htmlspecialchars( $open_value ) . '" />';
	$field_html .= ' - ';
	$field_html .= '<input type="text" class="yks_time_pick yks_hours_of_operation yks_time_pick_custom_margin ' . $override_class . '" name="' . esc_attr( $field_id ) . '[' . $i . '][' . esc_attr( $stl_day ) . '_close]" id="' . esc_attr( $field_id ) . '_' . esc_attr( $stl_day ) . '_close" value="' . htmlspecialchars( $close_value ) . '" />';
	$field_html .= '<input type="checkbox" class="yks_hours_of_operation_closed_override" name="' . esc_attr( $field_id ) . '[' . $i . '][' . esc_attr( $stl_day ) . '_closed_override]" id="' . esc_attr( $field_id ) . '_' . esc_attr( $stl_day ) . '_closed_override" value="1" ' . $override_check . ' />';
	$field_html .= '<label for="' . esc_attr( $field_id ) . '_' . esc_attr( $stl_day ) . '_closed_override" class="yks_hours_of_operation_closed_override_label"> Closed </label>';
	$field_html .= '</div>';
}
$field_html .= '</div>';

// Field description - always use block for this field
$field_html .= '<p class="yks_mbox_description yks_mbox_description_block">' . $field_desc . '</p>';

echo $field_html;
return;