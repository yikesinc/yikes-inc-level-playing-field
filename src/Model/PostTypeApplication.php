<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;

/**
 * Trait PostTypeApplication
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait PostTypeApplication {

	/**
	 * Get the post type slug to find.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicationManager::SLUG;
	}
}
