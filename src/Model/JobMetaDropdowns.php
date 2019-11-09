<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
* Trait JobMetaDropdowns
*
* @since   1.0.0
* @package Yikes\LevelPlayingField
*/
trait JobMetaDropdowns {

	/**
	 * Get dropdown options for job type.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_job_type_options() {
		return [
			'full_time'  => __( 'Full Time', 'level-playing-field' ),
			'part_time'  => __( 'Part Time', 'level-playing-field' ),
			'contract'   => __( 'Contract', 'level-playing-field' ),
			'internship' => __( 'Internship', 'level-playing-field' ),
			'per_diem'   => __( 'Per Diem', 'level-playing-field' ),
			'other'      => __( 'Other', 'level-playing-field' ),
		];
	}
}
