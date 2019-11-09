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
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 */
abstract class RestAPI implements Service, AssetsAware {

	use AssetsAwareness;

	/**
	 * Register the REST Registerables.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_assets();

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}
}
