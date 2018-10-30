<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Anonymizer;

/**
 * Interface AnonymizerInterface
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface AnonymizerInterface {

	/**
	 * Get anonmyized data.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $data The data to anonymize.
	 *
	 * @return mixed
	 */
	public function anonymize( $data );

	/**
	 * Get original form of data.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $data The data to reveal.
	 *
	 * @return mixed
	 */
	public function reveal( $data );
}
