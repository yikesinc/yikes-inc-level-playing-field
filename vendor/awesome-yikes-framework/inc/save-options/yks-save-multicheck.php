<?php

function yks_save_multicheck( $data ) {

	if ( ! empty( $data ) ) {

		// Make sure we're dealing with an array
		$data = ( ! is_array( $data ) ) ? array( $data ) : $data;
		$field_count = 0;
		$return_values = array();

		foreach ( $data as $field_value ) {
			if ( ! empty( $field_value ) ) {
				++$field_count;
				$return_values[ $field_count ] = $field_value;
			}
		}

		return $return_values;
	} else {
		return '';
	}
}
