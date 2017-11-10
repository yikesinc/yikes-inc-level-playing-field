<?php

// Function name must match file name minus ".php
function yks_save_text_three_fields( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	= array();
		$field1_array	= array();
		$field2_array	= array();
		$field3_array	= array();
		$return_counter = 0;
		$field1_counter	= 0;
		$field2_counter	= 0;
		$field3_counter	= 0;

		foreach ( $data as $key => $value ) {

			// First Field
			if ( strpos( $key, 'one' ) !== false ) {
				$field1_array[ $field1_counter ] = $value;
				$field1_counter++;
			}

			// Second Field
			if ( strpos( $key,  'two' ) !== false ) {
				$field2_array[ $field2_counter ] = $value;
				$field2_counter++;
			}

			// Third Field
			if ( strpos( $key,  'three' ) !== false ) {
				$field3_array[ $field3_counter ] = $value;
				$field3_counter++;
			}
		}

		// Create return array from our three fields' arrays
		if ( ! empty( $field1_array ) && ! empty( $field2_array ) && ! empty( $field3_array ) ) {

			foreach ( $field1_array as $index => $value ) {
				if ( isset( $field2_array[ $index ] ) && isset( $field3_array[ $index ] ) ) {
					$return_array[ $return_counter ]['one'] = $value;
					$return_array[ $return_counter ]['two'] = $field2_array[ $index ];
					$return_array[ $return_counter ]['three'] = $field3_array[ $index ];
					$return_counter++;
				}
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
