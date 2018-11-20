<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\AdminPage;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\View\AdminView;

/**
 * Class ExportApplicantsPage
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ExportApplicantsPage extends BaseAdminPage implements AssetsAware {

	use AssetsAwareness;

	const POST_TYPE = JobManager::SLUG;
	const PAGE_SLUG = 'lpf-export-applicants';
	const PRIORITY  = 15;
	const VIEW_URI  = 'views/applicant-export';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-export-page-admin-script';
	const JS_URI          = 'assets/js/export';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();

		$this->register_assets();

		add_filter( 'admin_enqueue_scripts', function( $hook ) {

			// Only enqueue on the export page.
			if ( $this->get_screen_base() !== $hook ) {
				return;
			}

			$this->enqueue_assets();
		} );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER ),
		];
	}

	/**
	 * Get the title to use for the admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be displayed in the title tags of the page when the menu is.
	 */
	protected function get_page_title() {
		return __( 'Export Applicants', 'yikes-level-playing-field' );
	}

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be used for the menu.
	 */
	protected function get_menu_title() {
		return __( 'Export', 'yikes-level-playing-field' );
	}
}
