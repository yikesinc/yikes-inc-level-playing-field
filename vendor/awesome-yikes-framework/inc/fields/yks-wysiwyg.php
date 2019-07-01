<?php
/**** WYSIWYG Editor ****/

/**
*
* Standard WP WYSIWYG Editor
* Use the $field['options'] as the editor's options (defined in the field definition)
* The $desc_type logic is implemented but WYSIWYG's should always use block (inline is just a less top-padded block display)
* No repeating logic
*
*/

// Setup our defaults
$field_values	 = ( isset( $meta ) ) ? $meta : '';
$field_options	 = ( isset( $field['options'] ) ) ? $field['options'] : array();
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';

// WP wysiwyg editor
wp_editor( $field_values, $field_id, $field_options );

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
