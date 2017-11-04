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
			// Custom capabilities.
			Capabilities::VIEW_ANONYMIZED_APPLICATIONS => true,
			Capabilities::MESSAGE_APPLICANTS           => true,
			Capabilities::UNANONYMIZE                  => false,

			// CPT capabilities.
			Capabilities::EDIT_JOB                     => true,
			Capabilities::EDIT_JOBS                    => true,
			Capabilities::EDIT_OTHERS_JOBS             => true,
			Capabilities::PUBLISH_JOBS                 => true,
			Capabilities::READ_JOB                     => true,
			Capabilities::READ_PRIVATE_JOBS            => true,
			Capabilities::DELETE_JOB                   => true,
			Capabilities::EDIT_APPLICATION             => true,
			Capabilities::EDIT_APPLICATIONS            => true,
			Capabilities::EDIT_OTHERS_APPLICATIONS     => true,
			Capabilities::PUBLISH_APPLICATIONS         => true,
			Capabilities::READ_APPLICATION             => true,
			Capabilities::READ_PRIVATE_APPLICATIONS    => true,
			Capabilities::DELETE_APPLICATION           => true,
			Capabilities::EDIT_APPLICANT               => true,
			Capabilities::EDIT_APPLICANTS              => true,
			Capabilities::EDIT_OTHERS_APPLICANTS       => true,
			Capabilities::PUBLISH_APPLICANTS           => true,
			Capabilities::READ_APPLICANT               => true,
			Capabilities::READ_PRIVATE_APPLICANTS      => true,
			Capabilities::DELETE_APPLICANT             => true,

			// Native capabilities.
			'read'                                     => true,
		];
	}
}
