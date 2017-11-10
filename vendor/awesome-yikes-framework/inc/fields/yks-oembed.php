<?php
/**** Embed Field ****/

/**
*
* Embed - a single input field and an embed preview div
* After a value is entered, we fire off an AJAX call (yks_oembed_ajax_results()) to grab an embed preview
*
* Due to the way the preview code/HTML works:
*	- This field does NOT support the desc_type => 'inline'/'block'
*	- This field does NOT support repeating
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

// Default embed options, filtered
$embed_options	 = apply_filters( 'yikes_awesome_framework_oembed_options', array( 'width' => '400' ) );

// if ( $field_repeating === true ) {

// 	// Enqueue our JS file for repeating fields
// 	wp_enqueue_script( 'yks-oembed', YKS_MBOX_URL . 'js/fields/min/yks-oembed.min.js', array( 'jquery' ) );

// 	// Container for repeating fields
// 	$field_html .= '<ul class="yks_oembed_container" id="container_' . $field_id . '">';

// 	// Loop through the fields
// 	foreach ( $field_values as $value ) {

// 		$embed_html = wp_oembed_get( esc_url( $value ), $embed_options );

// 		$field_counter++;

// 		// We hide the delete button for the first field
// 		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

// 		// <li>, input field, delete button
// 		$field_html .= '<li class="yks_oembed_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
// 		$field_html .= '<input type="text" class="yks_oembed yks_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[' . $field_counter . ']"  id="' . esc_attr( $field_id ) . '_' . $field_counter . '" value="' . htmlspecialchars( $value ) . '" />';
// 		$field_html .= 	'<span class="yks_oembed_delete dashicons dashicons-dismiss" ' . $style . '></span>';

// 		// Add the embed container HTML (even if we don't have valid embed code)
// 		$field_html .= '<div class="yks_upstat ui-helper-clearfix embed_wrap" id="' . esc_attr( $field_id ) . '_' . $field_counter . '_status">';
// 		$field_html .= 	'<div class="embed_status">';

// 		// If we have valid embed code, display it
// 		if ( $embed_html !== false ) {
// 			$field_html .= $embed_html;
// 			$field_html .= '<span class="yks_hide_ubutton" data-field-id="' . esc_attr( $field_id ) . '_' . $field_counter . '"  data-oembed="delete" title="Remove Embed Preview"></span>';
// 		} else {

// 			// If we don't have valid embed code, display a lil message
// 			$field_html .= '<p> URL is not a valid oEmbed URL. </p>';
// 		}

// 		$field_html .= 	'</div>';
// 		$field_html .= '</div>';
// 		$field_html .= '</li>';

// 		$field_html .= '<li class="dummy_li"></li>';

// 		// Close container
// 		$field_html .= '</ul>';

// 		// Add field button
// 		$field_html .= '<div class="yks_oembed_add_container">';
// 		$field_html .= 	'<span class="yks_oembed_add button button-primary" data-field-count="' . $field_counter . '">Add A Field</span>';
// 		$field_html .= '</div>';

// 		// We don't do 'desc_type' for this field - just add it as a block element
// 		$field_html .= '<p class="yks_mbox_description">' . $field_desc . '</p>';
// 	}
// } else {

// Get the field value
$value		= isset( $field_values[0] ) ? $field_values[0] : '';
$embed_html = ( ! empty( $value ) ) ? wp_oembed_get( esc_url( $value ), $embed_options ) : false;

// Not a repeating field - just add the input fields
$field_html .= '<input type="text" class="yks_oembed yks_non_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '"  id="' . esc_attr( $field_id ) . '" value="' . htmlspecialchars( $value ) . '" />';

// We don't do 'desc_type' for this field - just add it as a block element before the preview code
$field_html .= '<p class="yks_mbox_description">' . $field_desc . '</p>';

// Add the embed container HTML (even if we don't have valid embed code)
$field_html .= '<div class="yks_upstat ui-helper-clearfix embed_wrap" id="' . esc_attr( $field_id ) . '_status">';

// If we have valid embed code, display it
if ( $embed_html !== false ) {
	$field_html .= '<div class="embed_status">';
	$field_html .= $embed_html;
	$field_html .= '<span class="yks_hide_ubutton" data-field-id="' . esc_attr( $field_id ) . '"  data-oembed="delete" title="Remove Embed Preview"></span>';
	$field_html .= '</div>';
} elseif ( ! empty( $value ) ) {

	// If we don't have valid embed code, display a lil message
	$field_html .= '<p> URL is not a valid oEmbed URL. </p>';
}

$field_html .= '</div>';

//}

// Display our field on the page
echo $field_html;
return;
