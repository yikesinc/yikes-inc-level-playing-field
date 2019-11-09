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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface AnonymizerInterface {

	/**
	 * Get anonmyized data.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data The data to anonymize.
	 *
	 * @return mixed
	 */
	public function anonymize( $data );

	/**
	 * Get original form of data.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $data The data to reveal.
	 *
	 * @return mixed
	 */
	public function reveal( $data );
}
