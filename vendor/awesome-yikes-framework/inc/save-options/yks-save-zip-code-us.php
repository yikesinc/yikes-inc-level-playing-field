<?php

// Function name must match file name minus ".php
function yks_save_zip_code_us( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	= array();
		$zip5_array		= array();
		$zip4_array		= array();
		$return_counter	= 0;
		$zip5_counter	= 0;
		$zip4_counter	= 0;

		foreach ( $data as $key => $value ) {

			// Zip 5 Field
			if ( strpos( $key, 'zip5' ) !== false ) {
				$zip5_array[ $zip5_counter ] = $value;
				$zip5_counter++;
			}

			// Zip 4 Field
			if ( strpos( $key,  'zip4' ) !== false ) {
				$zip4_array[ $zip4_counter ] = $value;
				$zip4_counter++;
			}
		}

		// Create return array from our two fields' arrays
		if ( ! empty( $zip5_array ) ) {

			foreach ( $zip5_array as $index => $value ) {
				$zip4 = isset( $zip4_array[ $index ] ) && ! empty( $zip4_array[ $index ] ) ? $zip4_array[ $index ] : '';
				$return_array[ $return_counter ]['zip5'] = $value;
				$return_array[ $return_counter ]['zip4'] = $zip4;
				$return_counter++;
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
