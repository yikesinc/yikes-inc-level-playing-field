<?php
/**
 * YIKES Inc. Awesome Framework.
 *
 * Toggle Metabox Save Function.
 *
 * @package   Yikes\AwesomeFramework
 * @author    Ebonie Butler
 */

function yks_save_toggle( $data ) {

	if ( ! empty( $data ) ) {

		// Make sure we're dealing with an array
		$data = ( ! is_array( $data ) ) ? array( $data ) : $data;
		$field_count = 0;
		$return_values = array();

		foreach ( $data as $field_value ) {
			if ( $field_value != '' ) {
				++$field_count;
				$return_values[ $field_count ] = $field_value;
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
