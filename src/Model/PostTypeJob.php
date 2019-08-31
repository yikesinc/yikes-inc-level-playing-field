<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\JobManager;

/**
 * Trait PostTypeJob
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait PostTypeJob {

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return JobManager::SLUG;
	}
}
