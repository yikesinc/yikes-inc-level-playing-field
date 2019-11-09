<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Yikes\LevelPlayingField\Service;

/**
 * Class AdminStyles
 *
 * Handles registration of stylesheet for the entire admin area.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class AdminStyles implements Service, AssetsAware {

	use AssetsAwareness;

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_assets();

		add_action( 'admin_enqueue_scripts', function() {
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
			new StyleAsset( 'lpf-admin-css', 'assets/css/admin' ),
		];
	}
}
