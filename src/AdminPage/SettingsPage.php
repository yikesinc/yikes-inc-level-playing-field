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
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Settings\Settings;

/**
 * Class SettingsPage
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class SettingsPage extends BaseAdminPage implements AssetsAware {

	use AssetsAwareness;

	const PAGE_SLUG     = 'lpf-settings';
	const PRIORITY      = 50;
	const VIEW_URI      = 'views/settings';
	const SETTINGS_SLUG = 'lpf_settings';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-settings-script';
	const JS_URI          = 'assets/js/settings';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;
	const CSS_HANDLE      = 'lpf-settings-styles';
	const CSS_URI         = 'assets/css/settings';

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
		$script = new ScriptAsset(
			self::JS_HANDLE,
			self::JS_URI,
			self::JS_DEPENDENCIES,
			self::JS_VERSION,
			ScriptAsset::ENQUEUE_FOOTER
		);

		$script->add_localization(
			'lpf_settings_data',
			[
				'ajax'     => [
					'save_nonce'  => wp_create_nonce( 'save_settings' ),
					'save_action' => 'lpf_save_settings',
				],
				'settings' => wp_json_encode( new Settings() ),
				'strings'  => [
					'save_success' => __( 'Success: Settings Saved.', 'level-playing-field' ),
					'save_error'   => __( 'Error: The settings could not be saved.', 'level-playing-field' ),
				],
			]
		);

		$this->assets = [
			$script,
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
		return __( 'Settings &ndash; Level Playing Field', 'level-playing-field' );
	}

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since 1.0.0
	 *
	 * @return string The text to be used for the menu.
	 */
	protected function get_menu_title() {
		return __( 'Settings', 'level-playing-field' );
	}

	/**
	 * Include the variables required for this admin page.
	 *
	 * @since 1.0.0
	 *
	 * @return array $context The context.
	 */
	protected function get_context() {
		return [
			'settings' => new Settings(),
		];
	}
}
