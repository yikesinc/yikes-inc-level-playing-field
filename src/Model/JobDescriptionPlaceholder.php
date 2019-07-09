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
* Trait JoDescriptionPlaceholder
*
* @since   %VERSION%
* @package Yikes\LevelPlayingField
*/
trait JobDescriptionPlaceholder {

	/**
	 * Get dropdown options for job type.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_job_description_placeholder() {
		return __( "Enter your job's description.", 'yikes-level-playing-field' );
	}
}
