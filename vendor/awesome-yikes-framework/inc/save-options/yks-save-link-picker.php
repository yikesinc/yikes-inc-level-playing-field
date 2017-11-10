<?php

function yks_save_link_picker( $data ) {

	if ( ! empty( $data ) ) {

		$return_array	= array();
		$field_array	= array();
		$return_counter = 0;
		$field_counter	= 0;

		foreach ( $data as $key => $value ) {

			if ( strpos( $key, 'select' ) !== false ) {

				$index_number = explode( '_', $key );
				$index_number = $index_number[ count( $index_number ) - 1 ];
				$field_array[ $field_counter ]['select'] = $value;
				$field_array[ $field_counter ]['input']  = isset( $data['input_' . $index_number ] ) ? $data['input_' . $index_number ] : '';
				$field_counter++;
			}
		}

		// Create return array from our three fields' arrays
		if ( ! empty( $field_array ) ) {

			foreach ( $field_array as $index => $value ) {

				// The dropdown value (select) takes precedence over the input field value (input)
				$value_to_save = '';
				if ( isset( $value['select'] ) && ! empty( $value['select'] ) && $value['select'] !== 'custom_url' && $value['select'] !== 'select' ) {
					$value_to_save = $value['select'];
				} else if ( isset( $value['input'] ) && ! empty( $value['input'] ) && $value['select'] !== 'select' ) {
					$value_to_save = $value['input'];
				}

				$return_array[ $return_counter ] = esc_url( $value_to_save );
				$return_counter++;
			}
		}

		if ( count( $return_array ) === 1 ) {
			$return_array = $return_array[0];
		}

		return $return_array;
	} else {
		return '';
	}
}
