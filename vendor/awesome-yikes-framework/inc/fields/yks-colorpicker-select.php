<?php
/**** Colorpicker Select ****/


/**
*
* Colorpicker Select - a dropdown of pre-defined color codes and a preview box w/ the chosen color
* Options come from $field['options'] array
*
* No repeating logic
*
*/

// Enqueue our JS file for updating the preview box
wp_enqueue_script( 'yks-colorpicker-select', YKS_MBOX_URL . 'js/fields/min/yks-colorpicker-select.min.js', array( 'jquery' ) );


// Setup our defaults
$field_default	 = ( isset( $field['std'] ) ) ? $field['std'] : '';
$field_values	 = ( isset( $meta ) && ! empty( $meta ) ) ? $meta : $field_default;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$field_options	 = ( isset( $field['options'] ) ) ? $field['options'] : array();

// We set up a 'preview' box that shows the selected color
$field_html .= '<div class="yks_colorpicker_select_preview" style="background-color: ' . $field_values . ';"></div>';

// Dropdown
$field_html .= '<select class="yks_colorpicker_select" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" value="' . $field_values . '">';
$field_html 	.= '<option value="">-- select --</option>';
foreach ( $field_options as $option ) {
	$option_color	= isset( $option['color'] ) ? $option['color'] : '';
	$option_name	= isset( $option['name'] ) ? $option['name'] : '';
	$selected		= ( $field_values === $option_color ) ? 'selected="selected"' : '';

	$field_html .= '<option value="' . esc_attr( $option_color ) . '" ' . $selected . '>' . $option_name . '</option>';
}
$field_html .= '</select>';

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
