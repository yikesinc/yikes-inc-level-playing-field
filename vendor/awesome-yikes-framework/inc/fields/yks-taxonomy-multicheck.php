<?php
/**** Taxonomy MultiCheck (checkboxes) ****/

/**
*
* Checkboxes for the different terms within the taxonomy
*
* Nothing is 'saved' for this field - if a taxonomy is selected we simply relate the $post and the $taxonomy
*
* So we need to check the current post's terms (get_the_terms) to determine whether any of the checkboxes should be pre-checked
*
*/

// Setup our defaults
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$field_taxonomy  = ( isset( $field['taxonomy'] ) ) ? $field['taxonomy'] : '';
$field_counter   = 0;
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
		$field_html .= '<ul class="yks_mbox-multicheck">';
		foreach ( $terms as $term ) {
			$field_counter++;
			$term_slug = isset( $term->slug ) ? $term->slug : '';
			$term_name = isset( $term->name ) ? $term->name : '';
			$checked   = isset( $assigned_terms_array[ $term_slug ] ) ? 'checked="checked"' : '';

			$field_html .= '<li>';
			$field_html .= '<input type="checkbox" class="yks_taxonomy_multicheck" name="' . esc_attr( $field_id ) . '[]" id="' . esc_attr( $field_id ) . '_' . $field_counter . '" value="' . esc_attr( $term_name ) . '"' . $checked . '>';
			$field_html .= '<label for="' . esc_attr( $field_id ) . '_' . $field_counter . '">' . $term_name . '</label>';
			$field_html .= '</li>';
		}
		$field_html .= '</ul>';
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
