<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Freddie Mixell
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\REST;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class RestAPI
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 */
abstract class RestAPI implements Service, AssetsAware {

	use AssetsAwareness;
	use RestRestrict;

	/**
	 * Register the REST Registerables.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

}
