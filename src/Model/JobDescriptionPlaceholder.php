<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
* Trait JobDescriptionPlaceholder
*
* @since   1.0.0
* @package Yikes\LevelPlayingField
*/
trait JobDescriptionPlaceholder {

	/**
	 * Get placeholder text for job description paragraph block.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_job_description_placeholder() {
		return __( "Enter your job's description.", 'level-playing-field' );
	}
}
