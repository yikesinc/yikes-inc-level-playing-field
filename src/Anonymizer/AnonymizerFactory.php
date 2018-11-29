<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Anonymizer;

use Yikes\LevelPlayingField\Exception\InvalidClass;

/**
 * Class AnonymizerFactory
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class AnonymizerFactory {

	/**
	 * Get the Anonymizer interface.
	 *
	 * @since %VERSION%
	 * @return AnonymizerInterface
	 * @throws InvalidClass When an invlid class is supplied to the filter.
	 */
	public static function get_anonymizer() {
		static $anonymizer = null;
		if ( null === $anonymizer ) {
			$class      = apply_filters( 'lpf_anonymizer_class', Base64::class );
			$anonymizer = new $class();

			if ( ! ( $anonymizer instanceof AnonymizerInterface ) ) {
				$anonymizer = null;
				throw InvalidClass::from_interface( $class, AnonymizerInterface::class );
			}
		}

		return $anonymizer;
	}
}
