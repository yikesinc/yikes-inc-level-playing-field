<?php
/**
 * Select Post Type w/ select2 Field.
 *
 * Dropdown of all posts based on the $field['post-type'] value.
 * This field is the same as `yks-select-post-type` except it uses the Select2 library to make it better.
 * Saves the selected post ID to postmeta.
 *
 * @package YIKES Awesome Framework
 */

// Setup our defaults.
$field_values    = isset( $meta ) ? $meta : '';
$field_values    = empty( $field_values ) && isset( $field['std'] ) ? $field['std'] : $field_values;
$field_html      = '';
$field_id        = isset( $field['id'] ) ? $field['id'] : '';
$field_desc      = isset( $field['desc'] ) ? $field['desc'] : '';
$desc_type       = isset( $field['desc_type'] ) ? $field['desc_type'] : 'block';
$repeat_btn_text = isset( $field['repeat_btn_text'] ) ? $field['repeat_btn_text'] : __( 'Add A Field', 'yikes-level-playing-field' );
$default_option  = isset( $field['default_option'] ) ? $field['default_option'] : __( 'Select Value', 'yikes-level-playing-field' );
$field_post_type = isset( $field['post-type'] ) ? $field['post-type'] : '';
$field_post_type = empty( $field_post_type ) && isset( $field['post-types'] ) ? $field['post-types'] : $field_post_type; // Legacy.
$field_posts     = get_posts_by_posttype( $field_post_type );

// Create dropdown if we have $field_posts.
if ( ! empty( $field_posts ) ) {

	// Enqueue Select2 CSS/JS.
	wp_enqueue_style( 'select2-css-4.0.3', YKS_MBOX_URL . 'css/select2/select2.min.css', array(), YIKES_Awesome_Framework_Version, 'all' );
	wp_enqueue_script( 'select2-js-4.0.3', YKS_MBOX_URL . 'js/select2/select2.min.js', array( 'jquery' ), YIKES_Awesome_Framework_Version, true );

	$field_html .= '<select class="yks_select_post_type_select2 select2_init" name="' . esc_attr( $field_id ) . '" id="' . esc_attr( $field_id ) . '">';
	$field_html .= '<option value="">' . esc_html( $default_option ) . '</option>';

	foreach ( $field_posts as $post_array ) {
		$post_name   = isset( $post_array['name'] ) ? $post_array['name'] : '';
		$post_id     = isset( $post_array['value'] ) ? $post_array['value'] : '';
		$post_slug   = isset( $post_array['slug'] ) ? $post_array['slug'] : '';
		$selected    = (string) $field_values === (string) $post_id || (string) $field_values === (string) $post_slug ? 'selected="selected"' : '';
		$field_html .= '<option value="' . esc_attr( $post_id ) . '"' . esc_attr( $selected ) . '>';
		$field_html .= esc_html( $post_name );
		$field_html .= isset( $field['std'] ) && (string) $field['std'] === (string) $post_id || (string) $field['std'] === (string) $post_slug ? ' (Default)' : '';
		$field_html .= '</option>';
	}

	$field_html .= '</select>';
} else {

	$post_type_obj = get_post_type_object( $field_post_type );

	// Add a "create" button pointing to the create post page.
	if ( is_object( $post_type_obj ) ) {

		$field_html .= '<p><a class="button button-secondary" href="' . esc_url( add_query_arg( array( 'post_type' => $post_type_obj->name ), admin_url( 'post-new.php' ) ) ) . '">';
		// Translators: %1 is the post object's singular name.
		$field_html .= sprintf( esc_html__( 'Create %s', 'yikes-level-playing-field' ), $post_type_obj->labels->singular_name ) . '</a></p>';
		// Translators: %1 is the post object's name.
		$field_html .= '<em class="meta-error">' . sprintf( esc_html__( 'We were unable to find any %s.', 'yikes-level-playing-field' ), $post_type_obj->labels->name ) . '</em>';

	} else {

		// If we can't find the post type object, let the user know...
		$field_html .= '<em class="meta-error">' . esc_html__( 'We were unable to find the post type.', 'yikes-level-playing-field' ) . '</em>';
	}
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
