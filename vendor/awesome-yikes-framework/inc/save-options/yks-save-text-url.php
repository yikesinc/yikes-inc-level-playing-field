<?php

// Function name must match file name minus ".php
function yks_save_text_url( $data ) {

	if ( ! empty( $data ) ) {

		// Make sure we're dealing with an array
		$data = ( ! is_array( $data ) ) ? array( $data ) : $data;
		$field_count = 0;
		$return_values = array();

		foreach ( $data as $field_value ) {
			if ( $field_value != '' ) {
				++$field_count;
				$return_values[ $field_count ] = esc_url( $field_value );
			}
		}
		if ( $field_count === 1 ) {
			$return_values = $return_values[1];
		}

		return $return_values;
	} else {
		return '';
	}
}
