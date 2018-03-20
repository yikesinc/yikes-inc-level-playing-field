<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\LimitedJobManager;
use Yikes\LevelPlayingField\ListTable\ApplicationManager as ApplicationListTable;
use Yikes\LevelPlayingField\ListTable\JobManager as JobListTable;
use Yikes\LevelPlayingField\Metabox\ApplicationManager as ApplicationMetabox;
use Yikes\LevelPlayingField\Metabox\JobManager;
use Yikes\LevelPlayingField\Roles\Administrator;
use Yikes\LevelPlayingField\Roles\Editor;
use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;
use Yikes\LevelPlayingField\Shortcode\AllJobs;
use Yikes\LevelPlayingField\Shortcode\Application;
use Yikes\LevelPlayingField\Shortcode\Job;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class PluginFactory
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class PluginFactory {

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance.
	 *
	 * @since %VERSION%
	 *
	 * @return Plugin The plugin instance.
	 */
	public static function create() {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin( self::get_service_container() );
		}

		return $plugin;
	}

	/**
	 * Get the service container for our class.
	 *
	 * @since %VERSION%
	 * @return Container
	 */
	private static function get_service_container() {
		return new Container( [
			// CPTs.
			LimitedJobManager::class,
			ApplicationManager::class,
			ApplicantManager::class,

			// Taxonomies.
			JobCategory::class,
			JobStatus::class,
			ApplicantStatus::class,

			// Metaboxes.
			JobManager::class,
			ApplicationMetabox::class,

			// Custom List Tables.
			JobListTable::class,
			ApplicationListTable::class,

			// User roles.
			HiringManager::class,
			HumanResources::class,
			Administrator::class,
			Editor::class,

			// Shortcodes.
			AllJobs::class,
			Job::class,
			Application::class,
		] );
	}
}
