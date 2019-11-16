<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\REST;

use Yikes\LevelPlayingField\Roles\Capabilities;

/**
* Trait RestRestrict
*
* @since   1.0.0
* @package Yikes\LevelPlayingField
*/
trait RestRestrict {

	/**
	 * Permission Callback For Routes.
	 *
	 * @since 1.0.0
	 */
	public function can_edit_applications() {
		return current_user_can( Capabilities::EDIT_APPLICANTS );
	}
}
