<?php

/**
 * Handle our ajax functions here
 */

add_action( 'wp_ajax_my_action', 'add_application_field' );
function add_application_field() {
	// Include our helpers class (This allows us to use $helpers in our form field files)
	include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . '/includes/class-yikes-inc-level-playing-field-helpers.php' );
	$helpers = new Yikes_Inc_Level_Playing_Field_Helper( 'yikes-inc-level-playing-field', YIKES_LEVEL_PLAYING_FIELD_VERSION );

	// Dictate the field type to retreive
	$field_type = ( isset( $_POST['field_type'] ) ) ? esc_textarea( $_POST['field_type'] . '-field.php' ) : false;
	$fields_directory = YIKES_LEVEL_PLAYING_FIELD_PATH . '/includes/application-field-types/';
	if ( ! $field_type ) {
		return wp_send_json_error( array(
			'message' => __( 'There was an error retreiving your application field.', 'yikes-inc-level-playing-field' ),
		) );
	}

	// Start output buffering to catch our field markup
	ob_start();
	include( $fields_directory . $field_type );
	$field_markup = ob_get_contents();
	ob_get_clean();

	// return the field markup
	echo $field_markup;
	wp_die(); // this is required to terminate immediately and return a proper response
}
