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
 * Class HiringManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class HiringManager extends BaseRole {

	const SLUG = 'hiring-manager';

	/**
	 * Get the localized title for the role.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_title() {
		return esc_html__( 'Hiring Manager' );
	}

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
