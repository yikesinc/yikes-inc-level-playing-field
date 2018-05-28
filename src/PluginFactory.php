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
			LimitedJobManager::class    => 1,
			ApplicationManager::class   => 1,
			ApplicantManager::class     => 1,

			// Taxonomies.
			JobCategory::class          => 1,
			JobStatus::class            => 1,
			ApplicantStatus::class      => 1,

			// Metaboxes.
			JobManager::class           => 1,
			ApplicationMetabox::class   => 1,

			// Custom List Tables.
			JobListTable::class         => 1,
			ApplicationListTable::class => 1,

			// User roles.
			HiringManager::class        => 1,
			HumanResources::class       => 1,
			Administrator::class        => 1,
			Editor::class               => 1,

			// Shortcodes.
			AllJobs::class              => 1,
			Job::class                  => 1,
			Application::class          => 1,
		] );
	}
}
