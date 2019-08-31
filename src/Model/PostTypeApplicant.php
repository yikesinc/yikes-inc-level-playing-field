<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;

/**
 * Trait PostTypeApplicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait PostTypeApplicant {

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicantManager::SLUG;
	}
}
