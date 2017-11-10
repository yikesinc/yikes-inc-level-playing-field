<?php
/**** Star Columns Single Checkbox ****/

/**
*
* A single checkbox with a label wrapped around it
*
* Because checkboxes won't save a value if their not checked off, so repeating would only save X amount of checked checkboxes
*
* Important:
* To properly use this field remember to set a value of 1
* eg:
* array(
*    'name' => 'Sample Checkbox Field with column star values',
*    'desc' => 'This is a sample checkbox field.',
*    'id'   => $prefix . 'sample_checkbox',
*    'value' => 1,
*    'type' => 'star',
* 	 'star_color' = '#000000', // Default #000000
* 	 'dashicon' = 'ashicons-star', // Dashicon support.  Default 'dashicons-star',
* )
*
*/

// Setup our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_value	 = ( isset( $field['value'] ) ) ? $field['value'] : 1;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';



	// Get the field value
$value	 = isset( $field_values[0] ) ? $field_values[0] : '';
$checked = ( (string) $value === (string) $field_value ) ? 'checked="checked"' : '';

// Not a repeating field - no loop necessary, just add the HTML
$field_html .= '<input type="checkbox" class="yks_checkbox" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '"' . $checked . '/>';


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
