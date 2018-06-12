<?php

function yks_save_file( $data ) {

	if ( ! empty( $data ) ) {

		$return_array	= array();
		$return_counter = 0;

		foreach ( $data as $key => $value ) {
			if ( strpos( $key, 'url' ) !== false && ! empty( $value ) ) {

				// e.g. 'url_4' => 4
				$name_array = explode( '_', $key );
				$index_num  = ( isset( $name_array[1] ) && is_numeric( $name_array[1] ) ) ? $name_array[1] : '';

				$url_value = $value;
				$id_value  = isset( $data[ 'id_' . $index_num ] ) ? $data[ 'id_' . $index_num ] : '';

				$return_array[ $return_counter ] = array(
					'url' => $url_value,
					'id' => $id_value,
				);
				$return_counter++;
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
