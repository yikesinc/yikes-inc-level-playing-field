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
 * Class Applicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Applicant extends BaseRole {

	const SLUG = 'applicant';

	/**
	 * Get the localized title for the role.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_title() {
		return esc_html__( 'Applicant', 'yikes-level-playing-field' );
	}

	/**
	 * Get the capability array for the role.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_caps() {
		return [
			// General capabilities, similar to Author capabilities.
			'upload_files'                          => true,
			'read'                                  => true,

			// Ability to publish and read their own applications.
			Capabilities::EDIT_APPLICANTS           => true,
			Capabilities::EDIT_PUBLISHED_APPLICANTS => true,
			Capabilities::PUBLISH_APPLICANTS        => true,
			Capabilities::READ_APPLICANT            => true,
		];
	}
}
