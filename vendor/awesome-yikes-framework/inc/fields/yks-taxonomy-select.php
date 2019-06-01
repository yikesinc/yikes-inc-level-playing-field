<?php
/**
 * Taxonomy Select (Dropdown) Field.
 *
 * Dropdown for the different terms within the taxonomy.
 * Nothing is 'saved' for this field - if a taxonomy is selected we add the term to the post.
 * So we need to check the current post's terms via `get_the_terms()` to determine whether the dropdown should be pre-selected
 *
 * @package YIKES Awesome Framework
 */

// Setup our defaults.
$field_html     = '';
$field_id       = isset( $field['id'] ) ? $field['id'] : '';
$field_desc     = isset( $field['desc'] ) ? $field['desc'] : '';
$desc_type      = isset( $field['desc_type'] ) ? $field['desc_type'] : 'block';
$default_option = isset( $field['default_option'] ) ? $field['default_option'] : __( 'Select', 'yikes-level-playing-field' );
$field_taxonomy = isset( $field['taxonomy'] ) ? $field['taxonomy'] : '';
$terms          = get_terms(
	array(
		'taxonomy'   => $field_taxonomy,
		'hide_empty' => false,
	)
);
$assigned_terms = get_the_terms( $post, $field_taxonomy );

$assigned_terms_array = array();
if ( $assigned_terms !== false && ! is_wp_error( $assigned_terms ) ) {
	foreach ( $assigned_terms as $assigned_term ) {
		$assigned_terms_array[ $assigned_term->slug ] = $assigned_term;
	}
}

if ( ! is_wp_error( $terms ) ) {
	if ( ! empty( $terms ) ) {
		$field_html .= '<select class="yks_taxonomy_select" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
		$field_html .= '<option value="">' . esc_html( $default_option ) . '</option>';
		foreach ( $terms as $assigned_term ) {
			$term_slug = isset( $assigned_term->slug ) ? $assigned_term->slug : '';
			$term_name = isset( $assigned_term->name ) ? $assigned_term->name : '';
			$selected  = isset( $assigned_terms_array[ $term_slug ] ) ? 'selected="selected"' : '';

			$field_html .= '<option value="' . esc_attr( $term_slug ) . '"' . esc_attr( $selected ) . '>' . esc_html( $term_name ) . '</option>';
		}
		$field_html .= '</select>';
	} else {

		// If we found the taxonomy but it's empty, let 'em know.
		$field_html .= '<em class="meta-error">' . esc_html__( 'No terms found.', 'yikes-level-playing-field' ) . '</em>';
	}
} else {

	// If we can't find the taxonomy, let 'em know.
	$field_html .= '<em class="meta-error">' . esc_html__( 'We were unable to find the taxonomy.', 'yikes-level-playing-field' ) . '</em>';
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
