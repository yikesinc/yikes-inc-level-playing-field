<?php
/**
 * Save the checkbox field.
 *
 * @package YIKES Awesome Framework
 */

/**
 * Save the checkbox field.
 *
 * @param array $data The $_POST data for this field.
 *
 * @return mixed The formatted data to save to postmeta.
 */
function yks_save_checkbox( $data ) {

	if ( ! empty( $data ) ) {

		// Make sure we're dealing with an array.
		$data          = ! is_array( $data ) ? array( $data ) : $data;
		$field_count   = 0;
		$return_values = array();

		foreach ( $data as $field_value ) {
			if ( ! empty( $field_value ) ) {
				++$field_count;
				$return_values[ $field_count ] = $field_value;
			}
		}
		if ( $field_count === 1 ) {
			$return_values = isset( $return_values[1] ) ? $return_values[1] : '';
		}

		return $return_values;
	} else {
		return '';
	}
}
