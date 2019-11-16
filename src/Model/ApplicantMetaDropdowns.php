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
* @since   1.0.0
* @package Yikes\LevelPlayingField
*/
trait ApplicantMetaDropdowns {

	/**
	 * Get dropdown options for schooling institution type.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_schooling_options() {
		return [
			'high_school'       => __( 'High School', 'level-playing-field' ),
			'two_year_college'  => __( '2-Year College', 'level-playing-field' ),
			'four_year_college' => __( '4-Year College', 'level-playing-field' ),
			'trade_school'      => __( 'Trade/Technical/Vocational School', 'level-playing-field' ),
			'graduate_school'   => __( 'Graduate School', 'level-playing-field' ),
		];
	}

	/**
	 * Get dropdown options for skill proficiency.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_skills_options() {
		return [
			'basic'        => __( 'Basic Knowledge', 'level-playing-field' ),
			'novice'       => __( 'Novice', 'level-playing-field' ),
			'intermediate' => __( 'Intermediate', 'level-playing-field' ),
			'advanced'     => __( 'Advanced', 'level-playing-field' ),
			'expert'       => __( 'Expert', 'level-playing-field' ),
		];
	}

	/**
	 * Get dropdown options for language proficiency.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_language_options() {
		return [
			'fluent'       => __( 'Fluent', 'level-playing-field' ),
			'professional' => __( 'Professional', 'level-playing-field' ),
			'limited'      => __( 'Limited', 'level-playing-field' ),
			'elementary'   => __( 'Elementary', 'level-playing-field' ),
		];
	}
}
