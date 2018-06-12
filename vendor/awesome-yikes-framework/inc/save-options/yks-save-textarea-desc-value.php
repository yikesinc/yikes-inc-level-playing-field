<?php

// Function name must match file name minus ".php
function yks_save_textarea_desc_value( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array		= array();
		$desc_field_array	= array();
		$val_field_array	= array();
		$return_counter 	= 0;
		$desc_field_counter	= 0;
		$val_field_counter	= 0;

		foreach ( $data as $key => $field ) {

			// Description
			if ( strpos( $key, 'desc' ) !== false ) {
				++$desc_field_counter;
				$desc_field_array[ $desc_field_counter ] = $field;
			}
			// Value
			if ( strpos( $key,  'val' ) !== false ) {
				++$val_field_counter;
				$val_field_array[ $val_field_counter ] = $field;
			}
		}
		// Create array from two arrays
		if ( ! empty( $desc_field_array ) && ! empty( $val_field_array ) ) {

			foreach ( $desc_field_array as $index => $value ) {
				if ( isset( $val_field_array[ $index ] ) ) {
					$return_array[ $return_counter ]['desc'] = $value;
					$return_array[ $return_counter ]['val'] = $val_field_array[ $index ];
					$return_counter++;
				}
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
