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
use Yikes\LevelPlayingField\Registerable;

/**
 * Class AssetsHandler.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class AssetsHandler implements Registerable {

	/**
	 * Assets known to this asset handler.
	 *
	 * @since 1.0.0
	 *
	 * @var Asset[]
	 */
	private $assets = [];

	/**
	 * Add a single asset to the asset handler.
	 *
	 * @since 1.0.0
	 *
	 * @param Asset $asset Asset to add.
	 */
	public function add( Asset $asset ) {
		$this->assets[ $asset->get_handle() ] = $asset;
	}

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		foreach ( $this->assets as $asset ) {
			$asset->register();
		}
	}

	/**
	 * Enqueue a single asset based on its handle.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle Handle of the asset to enqueue.
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	public function enqueue_handle( $handle ) {
		if ( ! array_key_exists( $handle, $this->assets ) ) {
			throw InvalidAssetHandle::from_handle( $handle );
		}
		$this->assets[ $handle ]->enqueue();
	}

	/**
	 * Dequeue a single asset based on its handle.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle Handle of the asset to enqueue.
	 *
	 * @throws InvalidAssetHandle If the passed-in asset handle is not valid.
	 */
	public function dequeue_handle( $handle ) {
		if ( ! array_key_exists( $handle, $this->assets ) ) {
			throw InvalidAssetHandle::from_handle( $handle );
		}
		$this->assets[ $handle ]->dequeue();
	}

	/**
	 * Enqueue all assets known to this asset handler.
	 *
	 * @since 1.0.0
	 *
	 * @param Asset|null $asset Optional. Asset to enqueue. If omitted, all
	 *                          known assets are enqueued.
	 */
	public function enqueue( Asset $asset = null ) {
		$assets = $asset ? [ $asset ] : $this->assets;
		foreach ( $assets as $asset_object ) {
			$asset_object->enqueue();
		}
	}
}
