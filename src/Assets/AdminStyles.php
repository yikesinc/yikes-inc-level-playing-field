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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class AdminStyles implements Service, AssetsAware {

	use AssetsAwareness;

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'admin_enqueue_scripts', function() {
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
			new StyleAsset( 'lpf-admin-css', 'assets/css/admin' ),
		];
	}
}