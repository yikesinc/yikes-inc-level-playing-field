<?php

// Function name must match file name minus ".php
function yks_save_text_desc_value( $data ) {

	/**
	Data comes in looking like:

		array(4) {
		  ["desc_1"]=>
		  string(2) "1a"
		  ["val_1"]=>
		  string(2) "1b"
		  ["desc_2"]=>
		  string(2) "2a"
		  ["val_2"]=>
		  string(2) "2b"
		}

	And we want to return it like:
		array(2) {
		  [1]=>
		  array(2) {
		    ["desc"]=>
		    string(2) "1a"
		    ["val"]=>
		    string(2) "1b"
		  }
		  [2]=>
		  array(2) {
		    ["desc"]=>
		    string(2) "2a"
		    ["val"]=>
		    string(2) "2b"
		  }
		}
	*/

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	= array();
		$desc_array		= array();
		$val_array		= array();
		$return_counter = 0;
		$desc_counter	= 0;
		$val_counter	= 0;

		foreach ( $data as $key => $value ) {

			// Description
			if ( strpos( $key, 'desc' ) !== false ) {
				$desc_array[ $desc_counter ] = $value;
				$desc_counter++;
			}

			// Value
			if ( strpos( $key,  'val' ) !== false ) {
				$val_array[ $val_counter ] = $value;
				$val_counter++;
			}
		}

		// Create return array from $desc and $val arrays
		if ( ! empty( $desc_array ) && ! empty( $val_array ) ) {

			foreach ( $desc_array as $index => $desc ) {
				if ( isset( $val_array[ $index ] ) ) {
					$return_array[ $return_counter ]['desc'] = $desc;
					$return_array[ $return_counter ]['val'] = $val_array[ $index ];
					$return_counter++;
				}
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
