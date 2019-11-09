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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class Administrator extends ExistingRole {

	const SLUG = 'administrator';

	/**
	 * Get the capability array for the role.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_caps() {
		return [
			Capabilities::VIEW_ANONYMIZED_APPLICATIONS  => true,
			Capabilities::MESSAGE_APPLICANTS            => true,
			Capabilities::UNANONYMIZE                   => false,

			// Job capabilities.
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

			// Application capabilities.
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

			// Applicant capabilities.
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
			Capabilities::CREATE_APPLICANTS             => false,

			// Admin Pages.
			Capabilities::VIEW_ADMIN_PAGES              => true,
		];
	}
}
