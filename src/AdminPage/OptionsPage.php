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

/**
 * Class OptionsPage
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class OptionsPage extends BaseAdminPage implements AssetsAware {

	use AssetsAwareness;

	const PAGE_SLUG = 'lpf-options';
	const PRIORITY  = 50;
	const VIEW_URI  = 'views/options';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-options-script';
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

			// This filter should only run on our export page.
			if ( 'jobs_page_' . static::PAGE_SLUG !== $hook ) {
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
		return __( 'Options - Level Playing Field', 'yikes-level-playing-field' );
	}

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be used for the menu.
	 */
	protected function get_menu_title() {
		return __( 'Options', 'yikes-level-playing-field' );
	}

	/**
	 * Get the slug name to refer to this menu by.
	 *
	 * @since %VERSION%
	 *
	 * @return string The slug name to refer to this menu by.
	 */
	protected function get_menu_slug() {
		return static::PAGE_SLUG;
	}
}
