<?php
/**
 * YIKES Inc. Awesome Framework.
 *
 * Toggle Metabox.
 *
 * Important:
 * To properly use this field remember to set a value of 1
 * eg:
 * array(
 *    'name'      => 'Sample Toggle Field',
 *    'desc'      => 'This is a sample toggle field.',
 *    'id'        => $prefix . 'sample_toggle',
 *    'value'     => 1,
 *    'type'      => 'toggle',
 * )
 *
 * @package   Yikes\AwesomeFramework
 * @author    Ebonie Butler
 */

// Setup our defaults.
$field_values = ! is_array( $meta ) ? [ $meta ] : $meta;
$field_html   = '';
$field_value  = isset( $field['value'] ) ? $field['value'] : 1;
$field_id     = isset( $field['id'] ) ? $field['id'] : '';
$field_desc   = isset( $field['desc'] ) ? $field['desc'] : '';
$desc_type    = isset( $field['desc_type'] ) ? $field['desc_type'] : 'block';

// Get the field value.
$value = isset( $field_values[0] ) ? $field_values[0] : '';

// Set up field attributes.
$field_attributes = [
	'id'    => $field_id,
	'class' => [ 'yks_toggle', 'switch-input' ],
	'name'  => $field_id,
	'value' => $field_value,
	'type'  => 'checkbox',
];

if ( checked( $value, $field_value, false ) ) {
	$field_attributes['checked'] = 'checked';
}

// Build the HTML.
$field_html .= '<label class="switch">';
$field_html .= '<input ';
foreach ( $field_attributes as $key => $value ) {
	if ( 'class' === $key ) {
		$field_html .= yks_return_attribute( $key, join( ' ', $value ) );
	} else {
		$field_html .= yks_return_attribute( $key, $value );
	}
}
$field_html .= ' />';
$field_html .= '<span class="switch-label" data-on="On" data-off="Off"></span>';
$field_html .= '<span class="switch-handle"></span>';
$field_html .= '</label>';


// Field description.
if ( 'inline' === $desc_type ) {

	// If desc_type is inline, use a span.
	$field_html .= '<span class="yks_mbox_description yks_mbox_description_inline">' . $field_desc . '</span>';
} elseif ( 'block' === $desc_type ) {

	// If desc_type is block, use a p.
	$field_html .= '<p class="yks_mbox_description yks_mbox_description_block">' . $field_desc . '</p>';
}

// Display our field on the page.
echo $field_html; // phpcs:ignore WordPress.Security.EscapeOutput
return;
