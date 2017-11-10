<?php

// Function name must match file name minus ".php
function yks_save_text_phone_number_desc_value( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {
		$return_array	= array();
		$field1_array	= array();
		$field2_array	= array();
		$field3_array	= array();
		$field4_array	= array();
		$return_counter = 0;
		$field1_counter	= 0;
		$field2_counter	= 0;
		$field3_counter	= 0;
		$field4_counter	= 0;

		foreach ( $data as $key => $value ) {

			// Checks if first value in key=>value pair is empty to prevent empty arrays within an array within an array...
			if ( empty( $value ) ) {
				return '';
			}

			// First Field
			if ( strpos( $key, 'name' ) !== false ) {
				$field1_array[ $field1_counter ] = $value;
				$field1_counter++;
			}

			// Second Field
			if ( strpos( $key,  'one' ) !== false ) {
				$field2_array[ $field2_counter ] = $value;
				$field2_counter++;
			}

			// Third Field
			if ( strpos( $key,  'two' ) !== false ) {
				$field3_array[ $field3_counter ] = $value;
				$field3_counter++;
			}

			// Fourth Field
			if ( strpos( $key,  'three' ) !== false ) {
				$field4_array[ $field4_counter ] = $value;
				$field4_counter++;
			}
		}

		// Create return array from our three fields' arrays
		if ( ! empty( $field1_array ) && ! empty( $field2_array ) && ! empty( $field3_array ) && ! empty( $field4_array ) ) {

			foreach ( $field1_array as $index => $value ) {
				if ( isset( $field2_array[ $index ] ) && isset( $field3_array[ $index ] ) && isset( $field4_array[ $index ] ) ) {
					$return_array[ $return_counter ]['name'] = $value;
					$return_array[ $return_counter ]['phone'] = array( $field2_array[ $index ], $field3_array[ $index ], $field4_array[ $index ] );
					$return_counter++;
				}
			}
		}
		return $return_array;
	} else {
		return '';
	}// End if().
}
