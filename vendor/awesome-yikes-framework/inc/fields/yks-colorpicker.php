<?php
/**** Colorpicker ****/

/**
*
* Colorpicker - uses native WordPress JS wpColorPicker() (wp-color-picker.js)
* Saved as e.g. #ffffff
*
* No repeating logic
*
*/

// Setup our defaults
$field_default	 = ( isset( $field['std'] ) ) ? $field['std'] : '';
$field_values	 = ( isset( $meta ) && ! empty( $meta ) ) ? $meta : $field_default;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';

$field_html .= '<input class="yks_color_pick yks_txt_small" type="text" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" value="' . htmlspecialchars( $field_values ) . '" />';

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
