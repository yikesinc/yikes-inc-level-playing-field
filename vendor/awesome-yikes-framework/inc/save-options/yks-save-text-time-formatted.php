<?php

// Function name must match file name minus ".php
function yks_save_text_time_formatted( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	 = array();
		$hours_array	 = array();
		$minutes_array	 = array();
		$am_pm_array	 = array();
		$return_counter	 = 0;
		$hours_counter	 = 0;
		$minutes_counter = 0;
		$am_pm_counter	 = 0;

		foreach ( $data as $key => $value ) {

			// First Field
			if ( strpos( $key, 'hour' ) !== false ) {
				$hours_array[ $hours_counter ] = $value;
				$hours_counter++;
			}

			// Second Field
			if ( strpos( $key,  'minute' ) !== false ) {
				$minutes_array[ $minutes_counter ] = $value;
				$minutes_counter++;
			}

			// Third Field
			if ( strpos( $key,  'ampm' ) !== false ) {
				$am_pm_array[ $am_pm_counter ] = $value;
				$am_pm_counter++;
			}
		}

		// Create return array from our three fields' arrays
		if ( ! empty( $hours_array ) && ! empty( $minutes_array ) && ! empty( $am_pm_array ) ) {

			foreach ( $hours_array as $index => $value ) {
				if ( isset( $minutes_array[ $index ] ) && isset( $am_pm_array[ $index ] ) ) {

					$hour = ( $am_pm_array[ $index ] === '2' ) ? (int) $value + 12 : $value;

					$return_array[ $return_counter ] = $hour . $minutes_array[ $index ];
					$return_counter++;
				}
			}
		}

		// If we're only dealing with 1 item, save it as a string instead of an array
		if ( count( $return_array ) === 1 && isset( $return_array[0] ) ) {
			$return_array = $return_array[0];
		}

		return $return_array;
	} else {
		return '';
	}
}
