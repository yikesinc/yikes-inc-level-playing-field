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
 * Class Editor
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class Editor extends ExistingRole {

	const SLUG = 'editor';

	/**
	 * Get the capability array for the role.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_caps() {
		return [
			Capabilities::VIEW_ANONYMIZED_APPLICATIONS  => true,
			Capabilities::MESSAGE_APPLICANTS            => false,
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
			Capabilities::EDIT_APPLICANT                => false,
			Capabilities::EDIT_APPLICANTS               => false,
			Capabilities::EDIT_OTHERS_APPLICANTS        => false,
			Capabilities::PUBLISH_APPLICANTS            => false,
			Capabilities::READ_APPLICANT                => false,
			Capabilities::READ_PRIVATE_APPLICANTS       => false,
			Capabilities::DELETE_APPLICANT              => false,
			Capabilities::DELETE_APPLICANTS             => false,
			Capabilities::DELETE_PRIVATE_APPLICANTS     => false,
			Capabilities::DELETE_PUBLISHED_APPLICANTS   => false,
			Capabilities::DELETE_OTHERS_APPLICANTS      => false,
			Capabilities::EDIT_PRIVATE_APPLICANTS       => false,
			Capabilities::EDIT_PUBLISHED_APPLICANTS     => false,
			Capabilities::CREATE_APPLICANTS             => false,

			// Admin Pages.
			Capabilities::VIEW_ADMIN_PAGES              => true,
		];
	}
}
