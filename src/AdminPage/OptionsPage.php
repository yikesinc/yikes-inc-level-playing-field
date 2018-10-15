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
use Yikes\LevelPlayingField\Options\Options;

/**
 * Class OptionsPage
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class OptionsPage extends BaseAdminPage implements AssetsAware {

	use AssetsAwareness;

	const PAGE_SLUG    = 'lpf-options';
	const PRIORITY     = 50;
	const VIEW_URI     = 'views/options';
	const OPTIONS_SLUG = 'lpf_options';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-options-script';
	const JS_URI          = 'assets/js/options';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;
	const CSS_HANDLE      = 'lpf-options-styles';
	const CSS_URI         = 'assets/css/options';

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

		$script = new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER );
		$script->add_localization(
			'options_data',
			[
				'ajax'    => [
					'url'         => admin_url( 'admin-ajax.php' ),
					'save_nonce'  => wp_create_nonce( 'save_options' ),
					'save_action' => 'save_options',
				],
				'options' => wp_json_encode( new Options( true ) ),
				'strings' => [
					'save_success' => __( 'Success: Settings Saved.', 'yikes-level-playing-field' ),
				],
			]
		);

		return [
			$script,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
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
	 * Include the variables required for this admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return array $context The context.
	 */
	protected function get_context() {
		return [
			'options' => new Options( true ),
		];
	}
}
