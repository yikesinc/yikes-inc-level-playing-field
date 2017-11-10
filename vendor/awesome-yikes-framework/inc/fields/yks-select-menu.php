<?php
/**** Select Menu Field ****/

/**
*
* Menu Field - Dropdown of all `nav_menu` terms
*
* Saved as (string) nav_menu ID
*
*/

// Setup our defaults
$field_values	 = isset( $meta ) ? $meta : '';
$field_values 	 = ( empty( $field_values ) && isset( $field['std'] ) ) ? $field['std'] : $field_values;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$nav_menus		 = get_terms( 'nav_menu' );

$field_html .= '<select class="yks_select_menu" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
$field_html .= '<option value="">-- select --</option>';
foreach ( $nav_menus as $menu ) {
	$menu_name	= ( is_object( $menu ) && isset( $menu->name ) ) ? $menu->name : '';
	$menu_id	= ( is_object( $menu ) && isset( $menu->term_id ) ) ? $menu->term_id : '';
	$selected	= ( (string) $field_values === (string) $menu_id ) ? 'selected="selected"' : '';
	$field_html .= '<option value="' . $menu_id . '"' . $selected . '>';
	$field_html .= ucwords( $menu_name );
	$field_html .= '</option>';
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
