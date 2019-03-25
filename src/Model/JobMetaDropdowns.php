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
* @since   %VERSION%
* @package Yikes\LevelPlayingField
*/
trait JobMetaDropdowns {

	/**
	 * Get dropdown options for job type.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_job_type_options() {
		return [
			'full_time'   => __( 'Full Time', 'yikes-level-playing-field' ),
			'part_time'   => __( 'Part Time', 'yikes-level-playing-field' ),
			'contract'    => __( 'Contract', 'yikes-level-playing-field' ),
			'internship'  => __( 'Internship', 'yikes-level-playing-field' ),
			'per_diem'    => __( 'Per Diem', 'yikes-level-playing-field' ),
			'other'       => __( 'Other', 'yikes-level-playing-field' ),
		];
	}
}
