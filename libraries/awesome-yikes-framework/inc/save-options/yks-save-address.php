<?php
/**
 * Save the address field.
 *
 * @package YIKES Awesome Framework
 */

/**
 * Save the address field.
 *
 * @param array $data The $_POST data for this field.
 *
 * @return mixed The formatted data to save to postmeta.
 */
function yks_save_address( $data ) {

	if ( ! empty( $data ) ) {

		$return_array   = array();
		$return_counter = 0;

		foreach ( $data as $key => $value ) {
			if ( strpos( $key, 'address-1' ) !== false ) {

				// e.g. 'address-1_4' => 4.
				$name_array = explode( '_', $key );
				$index_num  = ( isset( $name_array[1] ) && is_numeric( $name_array[1] ) ) ? $name_array[1] : '';

				$address_1 = $value;
				$address_2 = isset( $data[ 'address-2_' . $index_num ] ) ? $data[ 'address-2_' . $index_num ] : '';
				$city      = isset( $data[ 'city_' . $index_num ] ) ? $data[ 'city_' . $index_num ] : '';
				$state     = isset( $data[ 'state_' . $index_num ] ) ? $data[ 'state_' . $index_num ] : '';
				$province  = isset( $data[ 'province_' . $index_num ] ) ? $data[ 'province_' . $index_num ] : '';
				$country   = isset( $data[ 'country_' . $index_num ] ) ? $data[ 'country_' . $index_num ] : '';
				$postal    = isset( $data[ 'zip_' . $index_num ] ) ? $data[ 'zip_' . $index_num ] : '';

				$return_array[ $return_counter ] = array(
					'address-1' => $address_1,
					'address-2' => $address_2,
					'city'      => $city,
					'state'     => $state,
					'province'  => $province,
					'country'   => $country,
					'zip'       => $postal,
				);
				$return_counter++;
			}
		}

		return $return_array;
	} else {
		return '';
	}
}
