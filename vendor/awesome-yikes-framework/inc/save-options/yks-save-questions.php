<?php

/**
*
* This save function works a little differently than the other ones because we're trying to capture a checkbox (e.g. $data['required_1'])
* and this checkbox won't always exist in the array (i.e. it's not a true/false value, it's a "if it's checked off, it's true, if it isn't checked off, it doesn't exist)
*
* So what we do in this function is to loop through the input fields (the labels) and try to find a required value that corresponds to the label
* So if we're processing label_1, we check if required_1 exists. If it exists, it is true. If it doesn't exist, it is empty.
*
*/

function yks_save_questions( $data ) {

	if ( ! empty( $data ) && is_array( $data ) ) {

		$return_array	= array();
		$return_counter = 0;

		foreach ( $data as $key => $value ) {

			// Label (input field)
			if ( strpos( $key, 'label' ) !== false ) {

				// e.g. 'label_4' => 4
				$label_name_array = explode( '_', $key );
				$label_index_num  = ( isset( $label_name_array[1] ) && is_numeric( $label_name_array[1] ) ) ? $label_name_array[1] : '';

				// e.g. 'required_4'
				$label_index_name = 'required_' . $label_index_num;

				// Try to find the index e.g. required_4 in the data array
				$required = isset( $data[ $label_index_name ] ) ? $data[ $label_index_name ] : '';

				// Add to our return array
				$return_array[ $return_counter ]['label'] = $value;
				$return_array[ $return_counter ]['required'] = $required;
				$return_counter++;
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
