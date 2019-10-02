<?php
/**** File Field ****/

/**
*
* File - an input field w/ the "Upload File" button
* This field uses WP native file upload method (wp_enqueue_media() function is required to facilitate this)
*
* This field is setup to use desc_type inline/block - but keep it block.
*
*/

// Native WP File Uploader dependency
wp_enqueue_media();

// Setup our defaults
$field_values	 = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html		 = '';
$field_repeating = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
$field_counter	 = 0;
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$input_type 	 = isset( $field['view'] ) && ( $field['view'] === 'url' ) ? 'text' : 'hidden';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields
	wp_enqueue_script( 'yks-file', YKS_MBOX_URL . 'js/fields/min/yks-file.min.js', array( 'jquery' ) );

	// Container for repeating fields
	$field_html .= '<ul class="yks_file_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$url_value = isset( $value['url'] ) ? $value['url'] : '';
		$id_value  = isset( $value['id'] ) ? $value['id'] : '';

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_file_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html  .= '<input type="' . $input_type . '" class="yks_file yks_img_up" name="' . esc_attr( $field_id ) . '[url_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_url_' . $field_counter . '" value="' . htmlspecialchars( $url_value ) . '" />';
		$field_html  .= '<input class="yks_img_up_button button" type="button" value="Upload File" />';
		$field_html  .= '<input type="hidden" class="yks_img_up_id yks_file" name="' . esc_attr( $field_id ) . '[id_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_id_' . $field_counter . '" value="' . htmlspecialchars( $id_value ) . '"/>';
		$field_html  .= '<span class="yks_file_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html  .= '<div class="yks_upstat">';
		if ( ! empty( $url_value ) ) {
			$field_html  .= '<div class="img_status">';
			$field_html .= yks_get_preview_html_from_file_type( $id_value ,$url_value );
			$field_html   .= '<a class="yks_hide_ubutton" data-switch="single">Remove Image</a>';
			$field_html  .= '</div>';
		}
		$field_html  .= '</div>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_file_add_container">';
	$field_html  .= '<span class="yks_file_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : $field_values;

	// Defaults
	$url_value = isset( $value['url'] ) ? $value['url'] : '';
	$id_value  = isset( $value['id'] ) ? $value['id'] : '';

	// Not a repeating field - just add the input fields
	$field_html .= '<input type="' . $input_type . '" class="yks_file yks_img_up" name="' . esc_attr( $field_id ) . '[url_1]" id="' . esc_attr( $field_id ) . '_url" value="' . htmlspecialchars( $url_value ) . '"/>';
	$field_html .= '<input class="yks_img_up_button button" type="button" value="Upload File" />';
	$field_html .= '<input type="hidden" class="yks_img_up_id yks_file" name="' . esc_attr( $field_id ) . '[id_1]" id="' . esc_attr( $field_id ) . '_id" value="' . htmlspecialchars( $id_value ) . '"/>';
	$field_html .= '<div class="yks_upstat">';
	if ( ! empty( $url_value ) ) {
		$field_html .= '<div class="img_status">';
		$field_html .= yks_get_preview_html_from_file_type( $id_value ,$url_value );
		$field_html  .= '<a class="yks_hide_ubutton" data-switch="single">Remove Image</a>';
		$field_html .= '</div>';
	}
	$field_html .= '</div>';
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
