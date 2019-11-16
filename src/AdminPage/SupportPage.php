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
use Yikes\LevelPlayingField\Assets\StyleAsset;

/**
 * Class SettingsPage
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class SupportPage extends BaseAdminPage implements AssetsAware {

	use AssetsAwareness;

	const PAGE_SLUG = 'lpf-support';
	const PRIORITY  = 50;
	const VIEW_URI  = 'views/support';

	// Define the JavaScript file.
	const CSS_HANDLE = 'lpf-support-page-styles';
	const CSS_URI    = 'assets/css/support';

	/**
	 * Register hooks.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();

		$this->register_assets();

		add_filter( 'admin_enqueue_scripts', function( $hook ) {

			// Only enqueue on the settings page.
			if ( $this->get_screen_base() !== $hook ) {
				return;
			}

			$this->enqueue_assets();
		} );
	}

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
		$this->assets = [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}

	/**
	 * Get the title to use for the admin page.
	 *
	 * @since 1.0.0
	 *
	 * @return string The text to be displayed in the title tags of the page when the menu is.
	 */
	protected function get_page_title() {
		return __( 'Support - Level Playing Field', 'level-playing-field' );
	}

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since 1.0.0
	 *
	 * @return string The text to be used for the menu.
	 */
	protected function get_menu_title() {
		return __( 'Support', 'level-playing-field' );
	}
}
