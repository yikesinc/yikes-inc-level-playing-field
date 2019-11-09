<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Interface Capabilities
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface Capabilities {

	// General capabilities.
	const CAP_PREFIX                   = 'lpf_';
	const VIEW_ANONYMIZED_APPLICATIONS = self::CAP_PREFIX . 'view_anonymized_applications';
	const MESSAGE_APPLICANTS           = self::CAP_PREFIX . 'message_applicants';
	const UNANONYMIZE                  = self::CAP_PREFIX . 'unanonymize';

	// Job Capabilities.
	const EDIT_JOB              = 'edit_' . JobManager::SINGULAR_SLUG;
	const EDIT_JOBS             = 'edit_' . JobManager::SLUG;
	const EDIT_OTHERS_JOBS      = 'edit_others_' . JobManager::SLUG;
	const PUBLISH_JOBS          = 'publish_' . JobManager::SLUG;
	const READ_JOB              = 'read_' . JobManager::SINGULAR_SLUG;
	const READ_PRIVATE_JOBS     = 'read_private_' . JobManager::SLUG;
	const DELETE_JOB            = 'delete_' . JobManager::SINGULAR_SLUG;
	const DELETE_JOBS           = 'delete_' . JobManager::SLUG;
	const DELETE_PRIVATE_JOBS   = 'delete_private_' . JobManager::SLUG;
	const DELETE_PUBLISHED_JOBS = 'delete_published_' . JobManager::SLUG;
	const DELETE_OTHERS_JOBS    = 'delete_others_' . JobManager::SLUG;
	const EDIT_PRIVATE_JOBS     = 'edit_private_' . JobManager::SLUG;
	const EDIT_PUBLISHED_JOBS   = 'edit_published_' . JobManager::SLUG;
	const CREATE_JOBS           = 'create_' . JobManager::SLUG;

	// Applicant Capabilities.
	const EDIT_APPLICANT              = 'edit_' . ApplicantManager::SINGULAR_SLUG;
	const EDIT_APPLICANTS             = 'edit_' . ApplicantManager::SLUG;
	const EDIT_OTHERS_APPLICANTS      = 'edit_others_' . ApplicantManager::SLUG;
	const PUBLISH_APPLICANTS          = 'publish_' . ApplicantManager::SLUG;
	const READ_APPLICANT              = 'read_' . ApplicantManager::SINGULAR_SLUG;
	const READ_PRIVATE_APPLICANTS     = 'read_private_' . ApplicantManager::SLUG;
	const DELETE_APPLICANT            = 'delete_' . ApplicantManager::SINGULAR_SLUG;
	const DELETE_APPLICANTS           = 'delete_' . ApplicantManager::SLUG;
	const DELETE_PRIVATE_APPLICANTS   = 'delete_private_' . ApplicantManager::SLUG;
	const DELETE_PUBLISHED_APPLICANTS = 'delete_published_' . ApplicantManager::SLUG;
	const DELETE_OTHERS_APPLICANTS    = 'delete_others_' . ApplicantManager::SLUG;
	const EDIT_PRIVATE_APPLICANTS     = 'edit_private_' . ApplicantManager::SLUG;
	const EDIT_PUBLISHED_APPLICANTS   = 'edit_published_' . ApplicantManager::SLUG;
	const CREATE_APPLICANTS           = 'create_' . ApplicantManager::SLUG;

	// Application Capabilities.
	const EDIT_APPLICATION              = 'edit_' . ApplicationManager::SINGULAR_SLUG;
	const EDIT_APPLICATIONS             = 'edit_' . ApplicationManager::SLUG;
	const EDIT_OTHERS_APPLICATIONS      = 'edit_others_' . ApplicationManager::SLUG;
	const PUBLISH_APPLICATIONS          = 'publish_' . ApplicationManager::SLUG;
	const READ_APPLICATION              = 'read_' . ApplicationManager::SINGULAR_SLUG;
	const READ_PRIVATE_APPLICATIONS     = 'read_private_' . ApplicationManager::SLUG;
	const DELETE_APPLICATION            = 'delete_' . ApplicationManager::SINGULAR_SLUG;
	const DELETE_APPLICATIONS           = 'delete_' . ApplicationManager::SLUG;
	const DELETE_PRIVATE_APPLICATIONS   = 'delete_private_' . ApplicationManager::SLUG;
	const DELETE_PUBLISHED_APPLICATIONS = 'delete_published_' . ApplicationManager::SLUG;
	const DELETE_OTHERS_APPLICATIONS    = 'delete_others_' . ApplicationManager::SLUG;
	const EDIT_PRIVATE_APPLICATIONS     = 'edit_private_' . ApplicationManager::SLUG;
	const EDIT_PUBLISHED_APPLICATIONS   = 'edit_published_' . ApplicationManager::SLUG;
	const CREATE_APPLICATIONS           = 'create_' . ApplicationManager::SLUG;

	// Custom Taxonomies.
	const MANAGE_JOB_STATUS       = 'manage_' . JobStatus::SLUG;
	const MANAGE_APPLICANT_STATUS = 'manage_' . ApplicantStatus::SLUG;

	// Admin Pages.
	const VIEW_ADMIN_PAGES = self::CAP_PREFIX . 'view_admin_pages';
}
