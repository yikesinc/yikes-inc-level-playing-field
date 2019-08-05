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
use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Abstract class BaseRestAPI
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 */
abstract class BaseRestAPI implements Service, AssetsAware {

	use AssetsAwareness;

	/**
	 * Register the REST Registerables.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'rest_api_init', [ $this, 'api_init' ] );
	}

	/**
	 * Register all routes.
	 *
	 * @since %VERSION%
	 */
	public function api_init() {
		$this->register_routes();
	}

	/**
	 * Handle individual routes registering.
	 *
	 * @since %VERSION%
	 */
	abstract function register_routes();

	/**
	 * Permission Callback For Routes.
	 *
	 * @since %VERSION%
	 */
	public function check_api_permissions() {
		return current_user_can( Capabilities::EDIT_APPLICANTS );
	}

}
