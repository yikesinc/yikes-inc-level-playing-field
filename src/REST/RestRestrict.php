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
* @since   %VERSION%
* @package Yikes\LevelPlayingField
*/
trait RestRestrict {

	/**
	 * Permission Callback For Routes.
	 *
	 * @since %VERSION%
	 */
	public function can_edit_applications() {
		return current_user_can( Capabilities::EDIT_APPLICANTS );
	}

}
