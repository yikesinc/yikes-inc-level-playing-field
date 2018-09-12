<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Tools;

use Yikes\LevelPlayingField\Exception\InvalidAnonymousData;

/**
 * Class Base64Anonymizer.
 *
 * Converts data to base 64 in order to anonmyize it.
 *
 * It's worth noting that this is the simplest, and therefore least secure, method of anonymizing
 * data. While the data will be anonymized for most people who are not technically inclined,
 * it will be trivial for someone to reveal the data on their own.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class Base64Anonymizer implements AnonymizerInterface {

	/**
	 * Anonymize data with base64 encoding.
	 *
	 * @since %VERSION%
	 *
	 * @param string $data The data to anonmyize.
	 *
	 * @return string the anonymized data.
	 */
	public function anonymize( $data ) {
		// Make sure data is a string.
		$data = (string) $data;

		$anonymized = base64_encode( $data );

		return $anonymized;
	}

	/**
	 * Reveal anonymized data.
	 *
	 * @since %VERSION%
	 *
	 * @param string $data The data to reveal.
	 *
	 * @return string The revealed data.
	 * @throws InvalidAnonymousData When the data cannot be revealed.
	 */
	public function reveal( $data ) {
		// Make sure data is a string.
		$data = (string) $data;

		// Attempt to decode the data.
		$clear = base64_decode( $data );
		if ( false === $clear ) {
			throw InvalidAnonymousData::from_data( $data );
		}

		return $clear;
	}
}
