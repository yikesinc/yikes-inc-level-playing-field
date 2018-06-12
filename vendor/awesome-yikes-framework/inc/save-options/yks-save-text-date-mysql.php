<?php

function yks_save_text_date_mysql( $data ) {

	if ( ! empty( $data ) ) {

		// Make sure we're dealing with an array
		$data = ( ! is_array( $data ) ) ? array( $data ) : $data;
		$field_count = 0;
		$return_values = array();

		foreach ( $data as $field_value ) {
			if ( $field_value != '' ) {
				++$field_count;
				$unix_time = strtotime( $field_value );
				$mysql_time = date( 'Y-m-d', $unix_time ) . ' 00:00:00';
				$return_values[ $field_count ] = $mysql_time;
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
