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
 * Class HumanResources
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class HumanResources extends BaseRole {

	const SLUG = 'human-resources';

	/**
	 * Get the localized title for the role.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Human Resources', 'level-playing-field' );
	}

	/**
	 * Get the capability array for the role.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_caps() {
		return [
			// Custom capabilities.
			Capabilities::VIEW_ANONYMIZED_APPLICATIONS  => true,
			Capabilities::MESSAGE_APPLICANTS            => true,
			Capabilities::UNANONYMIZE                   => true,
			Capabilities::VIEW_ADMIN_PAGES              => true,

			// CPT capabilities.
			Capabilities::EDIT_JOB                      => true,
			Capabilities::EDIT_JOBS                     => true,
			Capabilities::EDIT_OTHERS_JOBS              => true,
			Capabilities::PUBLISH_JOBS                  => true,
			Capabilities::READ_JOB                      => true,
			Capabilities::READ_PRIVATE_JOBS             => true,
			Capabilities::DELETE_JOB                    => true,
			Capabilities::DELETE_JOBS                   => true,
			Capabilities::DELETE_PRIVATE_JOBS           => true,
			Capabilities::DELETE_PUBLISHED_JOBS         => true,
			Capabilities::DELETE_OTHERS_JOBS            => true,
			Capabilities::EDIT_PRIVATE_JOBS             => true,
			Capabilities::EDIT_PUBLISHED_JOBS           => true,
			Capabilities::CREATE_JOBS                   => true,
			Capabilities::EDIT_APPLICATION              => true,
			Capabilities::EDIT_APPLICATIONS             => true,
			Capabilities::EDIT_OTHERS_APPLICATIONS      => true,
			Capabilities::PUBLISH_APPLICATIONS          => true,
			Capabilities::READ_APPLICATION              => true,
			Capabilities::READ_PRIVATE_APPLICATIONS     => true,
			Capabilities::DELETE_APPLICATION            => true,
			Capabilities::DELETE_APPLICATIONS           => true,
			Capabilities::DELETE_PRIVATE_APPLICATIONS   => true,
			Capabilities::DELETE_PUBLISHED_APPLICATIONS => true,
			Capabilities::DELETE_OTHERS_APPLICATIONS    => true,
			Capabilities::EDIT_PRIVATE_APPLICATIONS     => true,
			Capabilities::EDIT_PUBLISHED_APPLICATIONS   => true,
			Capabilities::CREATE_APPLICATIONS           => true,
			Capabilities::EDIT_APPLICANT                => true,
			Capabilities::EDIT_APPLICANTS               => true,
			Capabilities::EDIT_OTHERS_APPLICANTS        => true,
			Capabilities::PUBLISH_APPLICANTS            => true,
			Capabilities::READ_APPLICANT                => true,
			Capabilities::READ_PRIVATE_APPLICANTS       => true,
			Capabilities::DELETE_APPLICANT              => true,
			Capabilities::DELETE_APPLICANTS             => true,
			Capabilities::DELETE_PRIVATE_APPLICANTS     => true,
			Capabilities::DELETE_PUBLISHED_APPLICANTS   => true,
			Capabilities::DELETE_OTHERS_APPLICANTS      => true,
			Capabilities::EDIT_PRIVATE_APPLICANTS       => true,
			Capabilities::EDIT_PUBLISHED_APPLICANTS     => true,
			Capabilities::CREATE_APPLICANTS             => true,

			// Native capabilities.
			'read'                                      => true,

			// Third party capabilities.
			'view_admin_dashboard'                      => true,
		];
	}
}
