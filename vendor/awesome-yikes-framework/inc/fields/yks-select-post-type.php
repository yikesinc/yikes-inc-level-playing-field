<?php
/**** Select Post Type Field ****/

/**
*
* Select Post Type Field - Dropdown of all posts based on the $field['post-type'] value
*
* Saved as (string) post_type ID
*
*/

// Setup our defaults
$field_values	 = isset( $meta ) ? $meta : '';
$field_values 	 = ( empty( $field_values ) && isset( $field['std'] ) ) ? $field['std'] : $field_values;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$field_post_type = ( isset( $field['post-type'] ) ) ? $field['post-type'] : '';
$field_post_type = ( empty( $field_post_type ) && isset( $field['post-types'] ) ) ? $field['post-types'] : $field_post_type;
$posts			 = get_posts_by_posttype( $field_post_type );

// Create dropdown if we have $posts
if ( ! empty( $posts ) ) {
	$field_html .= '<select class="yks_select_post_type" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
	$field_html .= '<option value="">-- select --</option>';
	foreach ( $posts as $post_array ) {
		$post_name = isset( $post_array['name'] ) ? $post_array['name'] : '';
		$post_id   = isset( $post_array['value'] ) ? $post_array['value'] : '';
		$post_slug = isset( $post_array['slug'] ) ? $post_array['slug'] : '';
		$selected = ( (string) $field_values === (string) $post_id || (string) $field_values === (string) $post_slug  ) ? 'selected="selected"' : '';
		$field_html .= '<option value="' . $post_id . '"' . $selected . '>';
		$field_html .= $post_name;
		$field_html .= '</option>';
	}
	$field_html .= '</select>';
} else {

	$post_type_obj = get_post_type_object( $field_post_type );

	// Add a "create" button pointing to the create post page
	if ( is_object( $post_type_obj ) ) {

		$field_html .= '<p><a class="button button-secondary" href="' . esc_attr( admin_url( 'post-new.php?post_type=' . $post_type_obj->name ) ) . '">Create ' . $post_type_obj->labels->singular_name . '</a></p>';
		$field_html .= '<em class="meta-error">We were unable to find any ' . $post_type_obj->labels->name . '.</em>';

	} else {

		// If we can't find the post type object, let the user know...
		$field_html .= '<em class="meta-error">We were unable to find the post type. </em>';
	}
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
