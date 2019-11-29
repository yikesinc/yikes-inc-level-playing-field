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
$field_values     = ! is_array( $meta ) ? [ $meta ] : $meta;
$field_html       = '';
$field_value      = isset( $field['value'] ) ? $field['value'] : 1;
$field_id         = isset( $field['id'] ) ? $field['id'] : '';
$field_desc       = isset( $field['desc'] ) ? $field['desc'] : '';
$field_attributes = isset( $field['attributes'] ) ? (array) $field['attributes'] : [];

// Get the field value.
$value = isset( $field_values[0] ) ? $field_values[0] : '';

// Set up field attributes.
$field_attributes = array_merge(
	$field_attributes,
	[
		'id'    => $field_id,
		'name'  => $field_id,
		'value' => $field_value,
		'type'  => 'checkbox',
	]
);

$field_attributes['class'] = isset( $field_attributes['class'] ) ? $field_attributes['class'] : [];

$field_attributes['class'] = array_unique( array_merge( $field_attributes['class'], [ 'yks_toggle-input' ] ) );

if ( checked( $value, $field_value, false ) ) {
	$field_attributes['checked'] = 'checked';
}

// Build the HTML.
$field_html .= '<label class="yks_toggle">';
$field_html .= '<input ';
foreach ( $field_attributes as $key => $value ) {
	if ( 'class' === $key ) {
		$field_html .= yks_return_attribute( $key, join( ' ', $value ) );
	} else {
		$field_html .= yks_return_attribute( $key, $value );
	}
}
$field_html .= ' />';

$field_html .= '<span class="yks_toggle-label" data-on="Required" data-off="Not Required"></span>';
$field_html .= '<span class="yks_toggle-handle"></span>';
$field_html .= '</label>';

// Display our field on the page.
echo $field_html; // phpcs:ignore WordPress.Security.EscapeOutput
return;
