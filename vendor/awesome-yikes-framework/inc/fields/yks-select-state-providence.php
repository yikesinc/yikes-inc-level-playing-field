<?php
/**** Select (dropdown) for States ****/

/**
*
* HTML Dropdown pre-filled with all U.S. states
* States dropdown comes from function yks_awesome_framework_states_array() (w/ a filter yikes-awesome-framework-states-select if needed)
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

// Get the array of states to make our dropdown
$states = yks_awesome_framework_states_array();

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-select-state-providence', YKS_MBOX_URL . 'js/fields/min/yks-select-state-providence.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_select_state_providence_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_select_state_providence_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<select class="select_state_dropdown yks_select_state_providence" name="' . esc_attr( $field_id ) . '[' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_' . $field_counter . '">';
		$field_html .= '<option value="">-- Select State --</option>';
		foreach ( $states as $option ) {
			$option_value	= ( isset( $option['value'] ) ) ? $option['value'] : '';
			$option_name	= ( isset( $option['name'] ) ) ? $option['name'] : '';
			$selected		= ( $value === $option_value ) ? 'selected="selected"' : '';

			$field_html .= '<option value="' . esc_attr( $option_value ) . '"' . $selected . '>' . $option_name . '</option>';
		}
		$field_html .= '</select>';


		$field_html .= '<span class="yks_select_state_providence_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_select_state_providence_add_container">';
	$field_html .= '<span class="yks_select_state_providence_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';

} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<select class="select_state_dropdown yks_select_state_providence" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
	$field_html .= '<option value="">-- Select State --</option>';
	foreach ( $states as $option ) {
		$option_value	= ( isset( $option['value'] ) ) ? $option['value'] : '';
		$option_name	= ( isset( $option['name'] ) ) ? $option['name'] : '';
		$selected		= ( $value === $option_value ) ? 'selected="selected"' : '';

		$field_html .= '<option value="' . esc_attr( $option_value ) . '"' . $selected . '>' . $option_name . '</option>';
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
