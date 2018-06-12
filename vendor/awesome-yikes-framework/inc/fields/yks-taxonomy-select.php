<?php
/**** Taxonomy Select (Dropdown) ****/

/**
*
* Dropdown for the different terms within the taxonomy
*
* Nothing is 'saved' for this field - if a taxonomy is selected we simply relate the $post and the $taxonomy
*
* So we need to check the current post's terms (get_the_terms) to determine whether the dropdown should be pre-selected
*
*/

// Setup our defaults
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$field_taxonomy  = ( isset( $field['taxonomy'] ) ) ? $field['taxonomy'] : '';
$terms			 = get_terms( array( 'taxonomy' => $field_taxonomy, 'hide_empty' => false ) );
$assigned_terms  = get_the_terms( $post, $field_taxonomy );

$assigned_terms_array = array();
if ( $assigned_terms !== false && ! is_wp_error( $assigned_terms ) ) {
	foreach ( $assigned_terms as $term ) {
		$assigned_terms_array[ $term->slug ] = $term;
	}
}

if ( ! is_wp_error( $terms ) ) {
	if ( ! empty( $terms ) ) {
		$field_html .= '<select class="yks_taxonomy_select" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
		$field_html .= '<option value="">-- select --</option>';
		foreach ( $terms as $term ) {
			$term_slug = isset( $term->slug ) ? $term->slug : '';
			$term_name = isset( $term->name ) ? $term->name : '';
			$selected  = isset( $assigned_terms_array[ $term_slug ] ) ? 'selected="selected"' : '';

			$field_html .= '<option value="' . $term_slug . '"' . $selected . '>' . $term_name . '</option>';
		}
		$field_html .= '</select>';
	} else {

		// If we found the taxonomy but it's empty, let 'em know
		$field_html .= '<em class="meta-error">This taxonomy is empty!</em>';
	}
} else {

	// If we can't find the taxonomy, let 'em know
	$field_html .= '<em class="meta-error">We were unable to find the taxonomy.</em>';
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
