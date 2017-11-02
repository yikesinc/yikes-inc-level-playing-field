<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

/**
 * Class Administrator
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Administrator extends ExistingRole {

	const SLUG = 'administrator';

	/**
	 * Get the capability array for the role.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_caps() {
		return [
			Capabilities::VIEW_ANONYMIZED_APPLICATIONS => true,
			Capabilities::MESSAGE_APPLICANTS           => true,
			Capabilities::UNANONYMIZE                  => false,
		];
	}
}
