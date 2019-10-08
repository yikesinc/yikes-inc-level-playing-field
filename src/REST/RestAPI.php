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

	/**
	 * Register the REST Registerables.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		add_filter( 'rest_url_prefix', [ $this, 'fix_rest_base' ], 9999 );
	}

	/**
	 * Fixes the rest base if a site isn't using permalinks.
	 *
	 * @since %VERSION%
	 */
	public function fix_rest_base( $prefix ) {
		if ( ! get_option('permalink_structure') ) {
			$prefix = '?rest_route=';
		}
		return $prefix;
	}
}
