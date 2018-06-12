<?php
/**** Multicheck Post Type Field ****/

/**
*
* Mutlicheck Post Type Field - Checkboxes of posts based on the $field['post-type'] value
*
* User can select multiple posts
*
* Saved as an array of post IDs
*
* No Repeating Logic
*
*/

// Setup our defaults
$field_values	 = isset( $meta ) ? $meta : '';
$field_values 	 = ( empty( $field_values ) && isset( $field['std'] ) ) ? $field['std'] : $field_values;
$field_html		 = '';
$field_id		 = ( isset( $field['id'] ) ) ? $field['id'] : '';
$field_desc		 = ( isset( $field['desc'] ) ) ? $field['desc'] : '';
$field_counter   = 1;
$desc_type		 = ( isset( $field['desc_type'] ) ) ? $field['desc_type'] : 'block';
$field_post_type = ( isset( $field['post-type'] ) ) ? $field['post-type'] : '';
$posts			 = get_posts_by_posttype( $field_post_type );

// Create dropdown if we have $posts
if ( ! empty( $posts ) ) {
	
	$field_html .= '<ul class="yks_multicheck_post_type_container">';	
	foreach ( $posts as $post_array ) {

		$post_name = isset( $post_array['name'] ) ? $post_array['name'] : '';
		$post_id   = isset( $post_array['value'] ) ? $post_array['value'] : '';
		$checked = ( is_array( $field_values ) && in_array( (string) $post_id, $field_values ) ) ? 'checked="checked"' : '';

		$field_html .= '<li>';
		$field_html .= '<input type="checkbox" class="yks_multicheck_post_type" name="' . esc_attr( $field_id ) . '[]" id="' . esc_attr( $field_id ) . '_' . $field_counter . '"  value="' . $post_id . '"' . $checked . '/>';
		$field_html .= '<label for="' . esc_attr( $field_id ) . '_' . $field_counter . '">' . $post_name . '</label>';
		$field_html .= '</li>';

		$field_counter++;
	}
	$field_html .= '</ul>';
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
