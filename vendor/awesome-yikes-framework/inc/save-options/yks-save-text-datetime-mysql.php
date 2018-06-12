<?php

function yks_save_text_datetime_mysql( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	= array();
		$date_array		= array();
		$time_array		= array();
		$return_counter = 0;
		$date_counter	= 0;
		$time_counter	= 0;

		foreach ( $data as $key => $value ) {

			// Date
			if ( strpos( $key, 'date' ) !== false ) {
				$date_array[ $date_counter ] = $value;
				$date_counter++;
			}

			// Time
			if ( strpos( $key,  'time' ) !== false ) {
				$time_array[ $time_counter ] = $value;
				$time_counter++;
			}
		}

		// Create return array from $date and $time arrays
		if ( ! empty( $date_array ) && ! empty( $time_array ) ) {

			foreach ( $date_array as $index => $date ) {
				if ( isset( $time_array[ $index ] ) ) {

					$time = $time_array[ $index ];
					$datetime = ( ! empty( $date ) && ! empty( $time ) ) ? strtotime( $date . ' ' . $time ) : '';
					$return_array[ $return_counter ] = date( 'Y-m-d H:i:s', $datetime );
					$return_counter++;
				}
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
