<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\PluginHelper;
use Yikes\LevelPlayingField\AdminPage\GoProPage;
use Yikes\LevelPlayingField\AdminPage\SettingsPage;
use Yikes\LevelPlayingField\AdminPage\SupportPage;
use Yikes\LevelPlayingField\Assets\AdminStyles;
use Yikes\LevelPlayingField\Blocks\JobListing;
use Yikes\LevelPlayingField\Blocks\JobListings;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\LimitedJobManager;
use Yikes\LevelPlayingField\Filters\Filters;
use Yikes\LevelPlayingField\ListTable\ApplicantManager as ApplicantListTable;
use Yikes\LevelPlayingField\ListTable\ApplicationManager as ApplicationListTable;
use Yikes\LevelPlayingField\ListTable\JobManager as JobListTable;
use Yikes\LevelPlayingField\Messaging\ApplicantMessaging;
use Yikes\LevelPlayingField\Metabox\ApplicantBasicInfo;
use Yikes\LevelPlayingField\Metabox\ApplicantInterviewDetails;
use Yikes\LevelPlayingField\Metabox\ApplicantManager as ApplicantMetabox;
use Yikes\LevelPlayingField\Metabox\ApplicationManager as ApplicationMetabox;
use Yikes\LevelPlayingField\Metabox\JobManager;
use Yikes\LevelPlayingField\Metabox\ReviewBanner;
use Yikes\LevelPlayingField\Metabox\ProBanner;
use Yikes\LevelPlayingField\REST\InterviewAPI;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\RequiredPages\ApplicationFormPage;
use Yikes\LevelPlayingField\Roles\Administrator;
use Yikes\LevelPlayingField\Roles\Applicant;
use Yikes\LevelPlayingField\Roles\Editor;
use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;
use Yikes\LevelPlayingField\Settings\SettingsManager;
use Yikes\LevelPlayingField\Shortcode\AllJobs;
use Yikes\LevelPlayingField\Shortcode\Application;
use Yikes\LevelPlayingField\Shortcode\Job;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;
use Yikes\LevelPlayingField\TemplateController\ApplicantMessagingTemplateController as ApplicantMessageTemplate;
use Yikes\LevelPlayingField\TemplateController\SingleApplicationsTemplateController as SingleApplications;
use Yikes\LevelPlayingField\TemplateController\SingleJobsTemplateController as SingleJobs;
use Yikes\LevelPlayingField\Transient\EmailRecipient;
use Yikes\LevelPlayingField\Widget\Dashboard\JobApplicants;
use Yikes\LevelPlayingField\Welcome;

/**
 * Class PluginFactory
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class PluginFactory {

	use PluginHelper;

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin The plugin instance.
	 */
	public function create() {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin( $this->get_service_container() );
		}

		return $plugin;
	}

	/**
	 * Get the service container for our class.
	 *
	 * @since 1.0.0
	 * @return Container
	 */
	private function get_service_container() {

		$services = new Container();

		// Filters.
		$services->add_service( Filters::class );

		// Blocks.
		if ( $this->is_new_editor_enabled() ) {
			$services->add_service( JobListing::class );
			$services->add_service( JobListings::class );
		}

		// CPTs.
		$services->add_service( LimitedJobManager::class );
		$services->add_service( ApplicationManager::class );
		$services->add_service( ApplicantManager::class );

		// Taxonomies.
		$services->add_service( JobCategory::class );
		$services->add_service( JobStatus::class );
		$services->add_service( ApplicantStatus::class );

		// Metaboxes.
		$services->add_service( JobManager::class );
		$services->add_service( ApplicationMetabox::class );
		$services->add_service( ApplicantMetabox::class );
		$services->add_service( ApplicantBasicInfo::class );
		$services->add_service( ApplicantInterviewDetails::class );
		$services->add_service( ReviewBanner::class );
		$services->add_service( ProBanner::class );

		// REST API.
		$services->add_service( InterviewAPI::class );

		// Custom list tables.
		$services->add_service( JobListTable::class );
		$services->add_service( ApplicationListTable::class );
		$services->add_service( ApplicantListTable::class );

		// User roles.
		$services->add_service( HiringManager::class );
		$services->add_service( HumanResources::class );
		$services->add_service( Applicant::class );
		$services->add_service( Administrator::class );
		$services->add_service( Editor::class );

		// Widgets.
		$services->add_service( JobApplicants::class );

		// Shortcodes.
		$services->add_service( AllJobs::class );
		$services->add_service( Job::class );
		$services->add_service( Application::class );

		// Assets.
		$services->add_service( AdminStyles::class );

		// Settings.
		$services->add_service( SettingsManager::class );

		// Admin pages.
		$services->add_service( SettingsPage::class );
		$services->add_service( GoProPage::class );
		$services->add_service( SupportPage::class );

		// Template overrides.
		$services->add_service( SingleJobs::class );
		$services->add_service( SingleApplications::class );

		// Messaging.
		$services->add_service( ApplicantMessaging::class );

		// Messaging template.
		$services->add_service( ApplicantMessageTemplate::class );

		// Required pages.
		$services->add_service( ApplicantMessagingPage::class );
		$services->add_service( ApplicationFormPage::class );

		// Transient Handling.
		$services->add_service( EmailRecipient::class );

		// Handle Welcome Message.
		$services->add_service( Welcome::class );

		return $services;
	}
}
