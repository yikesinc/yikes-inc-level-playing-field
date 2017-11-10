<?php
/**** Link Picker Field ****/

/**
*
* A field with (1) a dropdown for pages/posts/CPTs and (2) an input field for a URL
*
* The value saved is a URL (permalink for page/post/CPT or the custom URL)
*
* If the dropdown and the input field have values, take the link from the dropdown - it has precedence
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
$post_types		 = ( isset( $field['post-types'] ) ) ? $field['post-types'] : array( 'any' );
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : 'Add A Field';
$post_ids		 = get_post_ids_by_post_types( $post_types );

// Enqueue our JS file --- this handles some field logic as well as repeating fields
wp_enqueue_script( 'yks-link-picker', YKS_MBOX_URL . 'js/fields/min/yks-link-picker.min.js', array( 'jquery' ) );

if ( $field_repeating === true ) {

	// Container for repeating fields
	$field_html .= '<ul class="yks_link_picker_container" id="container_' . $field_id . '">';

	// Loop through the fields
	foreach ( $field_values as $value ) {

		$use_dropdown = false;

		$field_counter++;

		// We hide the delete button for the first field
		$style = ( $field_counter === 1 ) ? 'style="display: none;"' : '';

		// <li>, input field, delete button
		$field_html .= '<li class="yks_link_picker_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';


		// Dropdown for posts/pages/CPTs
		$field_html .= '<select class="yks_link_picker yks_link_picker_dropdown" name="' . esc_attr( $field_id ) . '[select_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_select_' . $field_counter . '">';
		$field_html .= '<option value="select"> -- select -- </option>';
		$field_html .= '<option value="custom_url">Custom URL</option>';
		foreach ( $post_ids as $post_id) {

			$post_url  = get_permalink( $post_id );
			$post_name = get_the_title( $post_id );
			$selected  = '';
			if ( $post_url === $value ) {
				$selected = 'selected="selected"';
				$use_dropdown = true;
			}

			$field_html .= '<option value="' . $post_url . '"' . $selected . '>';
			$field_html .= $post_name;
			$field_html .= '</option>';
		}
		$field_html .= '</select>';


		// Do not show the URL in the input field if it was chosen (if we can find the value) in the dropdown
		$input_field_value = '';
		$input_field_style = 'style="display: none;"';
		if ( $use_dropdown === false && ! empty( $value ) ) {
			$input_field_value = htmlspecialchars( $value );
			$input_field_style = '';
		}

		// Input field for links
		$field_html .= '<input ' . $input_field_style . ' type="text" class="yks_link_picker yks_link_picker_input yks_link_picker_input_repeating" name="' . esc_attr( $field_id ) . '[input_' . $field_counter . ']" id="' . esc_attr( $field_id ) . '_input_' . $field_counter . '" value="' . $input_field_value . '" />';
		$field_html .= '<span class="yks_link_picker_delete dashicons dashicons-dismiss" ' . $style . '></span>';
		$field_html .= '</li>';
	}

	// Close container
	$field_html .= '</ul>';

	// Add field button
	$field_html .= '<div class="yks_link_picker_add_container">';
	$field_html .= '<span class="yks_link_picker_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . $repeat_btn_text . '</span>';
	$field_html .= '</div>';
} else {

	$use_dropdown = false;

	// Get the field value
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	$field_html .= '<select class="yks_link_picker yks_link_picker_dropdown" name="' . esc_attr( $field_id ) . '[select_1]" id="' . esc_attr( $field_id ) . '_select_' . $field_counter . '">';
	$field_html .= '<option value="select">-- select --</option>';
	$field_html .= '<option value="custom_url">Custom URL</option>';
	foreach ( $post_ids as $post_id) {

		$post_url  = get_permalink( $post_id );
		$post_name = get_the_title( $post_id );
		$selected  = '';
		if ( $post_url === $value ) {
			$selected = 'selected="selected"';
			$use_dropdown = true;
		}

		$field_html .= '<option value="' . $post_url . '"' . $selected . '>';
		$field_html .= $post_name;
		$field_html .= '</option>';
	}
	$field_html .= '</select>';

	// Do not show the URL in the input field if it was chosen (if we can find the value) in the dropdown
	$input_field_value = '';
	$input_field_style = 'style="display: none;"';
	if ( $use_dropdown === false && ! empty( $value ) ) {
		$input_field_value = htmlspecialchars( $value );
		$input_field_style = '';
	}

	// Not a repeating field - no loop necessary, just add the HTML
	$field_html .= '<input ' . $input_field_style . ' type="text" class="yks_link_picker yks_link_picker_input" name="' . esc_attr( $field_id ) . '[input_1]" id="' . esc_attr( $field_id ) . '_input_1" value="' . $input_field_value . '" />';
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
