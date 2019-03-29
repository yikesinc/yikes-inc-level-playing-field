<?php
/**
 * Textarea Field.
 *
 * @package YIKES Awesome Framework
 */

// Setup our defaults.
$field_values    = ! is_array( $meta ) ? array( $meta ) : $meta;
$field_html      = '';
$field_repeating = isset( $field['repeating'] ) && true === $field['repeating'];
$field_counter   = 0;
$field_id        = isset( $field['id'] ) ? $field['id'] : '';
$field_desc      = isset( $field['desc'] ) ? $field['desc'] : '';
$desc_type       = isset( $field['desc_type'] ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : __( 'Add A Field', 'yikes-level-playing-field' );

if ( $field_repeating === true ) {

	// Enqueue our JS file for repeating fields.
	wp_enqueue_script( 'yks-textarea', YKS_MBOX_URL . 'js/fields/min/yks-textarea.min.js', array( 'jquery' ), YIKES_Awesome_Framework_Version, true );

	// Container for repeating fields.
	$field_html .= '<ul class="yks_textarea_container" id="container_' . esc_attr( $field_id ) . '">';

	// Loop through the fields.
	foreach ( $field_values as $value ) {

		$field_counter++;

		// We hide the delete button for the first field.
		$style = 1 === $field_counter ? 'display: none;' : '';

		// <li>, input field, delete button.
		$field_html .= '<li class="yks_textarea_field ui-state-default"><span data-code="f156" class="dashicons dashicons-sort" ></span>';
		$field_html .= '<textarea class="yks_textarea yks_repeating_input_fields_width" name="' . esc_attr( $field_id ) . '[' . esc_attr( $field_counter ) . ']" id="' . esc_attr( $field_id ) . '_' . esc_attr( $field_counter ) . '" cols="60" rows="10">' . esc_html( $value ) . '</textarea>';
		$field_html .= '<span class="yks_textarea_delete dashicons dashicons-dismiss" style="' . esc_attr( $style ) . '"></span>';
		$field_html .= '</li>';
	}

	// Close container.
	$field_html .= '</ul>';

	// Add field button.
	$field_html .= '<div class="yks_textarea_add_container">';
	$field_html .= '<span class="yks_textarea_add button button-primary" data-field-count="' . esc_attr( $field_counter ) . '">' . esc_html( $repeat_btn_text ) . '</span>';
	$field_html .= '</div>';
} else {

	// Get the field value.
	$value = isset( $field_values[0] ) ? $field_values[0] : '';

	// Not a repeating field - no loop necessary, just add the HTML.
	$field_html .= '<textarea class="yks_textarea" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '" cols="60" rows="10">';
	$field_html .= esc_html( $value );
	$field_html .= '</textarea>';
}

// Field description.
if ( $desc_type === 'inline' ) {

	// If desc_type is inline, use a span.
	$field_html .= '<span class="yks_mbox_description yks_mbox_description_inline">' . esc_html( $field_desc ) . '</span>';
} elseif ( $desc_type === 'block' ) {

	// If desc_type is block, use a p.
	$field_html .= '<p class="yks_mbox_description yks_mbox_description_block">' . esc_html( $field_desc ) . '</p>';
}

// Display our field on the page.
echo $field_html; // phpcs:ignore WordPress.Security.EscapeOutput
return;
