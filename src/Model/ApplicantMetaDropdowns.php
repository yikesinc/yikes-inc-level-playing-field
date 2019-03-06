<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
* Trait ApplicantMetaDropdowns
*
* @since   %VERSION%
* @package Yikes\LevelPlayingField
*/
trait ApplicantMetaDropdowns {

	/**
	 * Get dropdown options for schooling institution type.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_schooling_options() {
		return [
			'high_school'       => __( 'High School', 'yikes-level-playing-field' ),
			'two_year_college'  => __( '2-Year College', 'yikes-level-playing-field' ),
			'four_year_college' => __( '4-Year College', 'yikes-level-playing-field' ),
			'trade_school'      => __( 'Trade/Technical/Vocational School', 'yikes-level-playing-field' ),
			'graduate_school'   => __( 'Graduate School', 'yikes-level-playing-field' ),
		];
	}

	/**
	 * Get dropdown options for language profiency.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_language_options() {
		return [
			'basic'        => __( 'Basic Knowledge', 'yikes-level-playing-field' ),
			'novice'       => __( 'Novice', 'yikes-level-playing-field' ),
			'intermediate' => __( 'Intermediate', 'yikes-level-playing-field' ),
			'advanced'     => __( 'Advanced', 'yikes-level-playing-field' ),
			'expert'       => __( 'Expert', 'yikes-level-playing-field' ),
		];
	}
}
