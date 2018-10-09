<?php

function yks_save_map_picker( $data ) {

	// The API key gets stored as an option instead of post meta because it needs to be shared across posts.
	$API_Key = isset( $_POST['map-picker-api-key'] ) ? $_POST['map-picker-api-key'] : '';

	if ( ! empty( $API_Key ) ) {
		update_option( 'yks-awesome-framework-map-picker-api-key', $API_Key );
	}

	if ( ! empty( $data ) ) {

		// We don't have to do any transformations here, just return the data.
		return $data;
	}
}
