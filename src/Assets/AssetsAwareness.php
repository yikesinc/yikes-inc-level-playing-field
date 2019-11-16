<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Yikes\LevelPlayingField\Exception\InvalidAssetHandle;

/**
 * Trait AssetsAwareness
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
trait AssetsAwareness {

	/**
	 * Assets handler instance to use.
	 *
	 * @since 1.0.0
	 *
	 * @var AssetsHandler
	 */
	protected $assets_handler;

	/**
	 * Array of asset objects.
	 *
	 * @since 1.0.0
	 * @var Asset[]
	 */
	protected $assets = [];

	/**
	 * Get the array of known assets.
	 *
	 * @since 1.0.0
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		if ( empty( $this->assets ) ) {
			$this->load_assets();
		}

		return $this->assets;
	}

	/**
	 * Register the known assets.
	 *
	 * @since 1.0.0
	 */
	protected function register_assets() {
		foreach ( $this->get_assets() as $asset ) {
			$this->assets_handler->add( $asset );
		}
	}

	/**
	 * Enqueue the known assets.
	 *
	 * @since 1.0.0
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	protected function enqueue_assets() {
		foreach ( $this->get_assets() as $asset ) {
			$this->assets_handler->enqueue( $asset );
		}
	}

	/**
	 * Enqueue a single asset.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle Handle of the asset to enqueue.
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	protected function enqueue_asset( $handle ) {
		$this->assets_handler->enqueue_handle( $handle );
	}

	/**
	 * Set the assets handler to use within this object.
	 *
	 * @since 1.0.0
	 *
	 * @param AssetsHandler $assets Assets handler to use.
	 */
	public function with_assets_handler( AssetsHandler $assets ) {
		$this->assets_handler = $assets;
	}

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
		$this->assets = [];
	}
}
