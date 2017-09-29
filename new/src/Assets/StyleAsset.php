<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Closure;

/**
 * Class StyleAsset.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
class StyleAsset extends BaseAsset {

	const MEDIA_ALL    = 'all';
	const MEDIA_PRINT  = 'print';
	const MEDIA_SCREEN = 'screen';

	const DEFAULT_EXTENSION = 'css';

	/**
	 * Source location of the asset.
	 *
	 * @since %VERSION%
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * Dependencies of the asset.
	 *
	 * @since %VERSION%
	 *
	 * @var array<string>
	 */
	protected $dependencies;

	/**
	 * Version of the asset.
	 *
	 * @since %VERSION%
	 *
	 * @var string|bool|null
	 */
	protected $version;

	/**
	 * Media for which the asset is defined.
	 *
	 * @since %VERSION%
	 *
	 * @var string
	 */
	protected $media;

	/**
	 * Instantiate a StyleAsset object.
	 *
	 * @since %VERSION%
	 *
	 * @param string           $handle       Handle of the asset.
	 * @param string           $source       Source location of the asset.
	 * @param array            $dependencies Optional. Dependencies of the
	 *                                       asset.
	 * @param string|bool|null $version      Optional. Version of the asset.
	 * @param string           $media        Media for which the asset is
	 *                                       defined.
	 */
	public function __construct(
		$handle,
		$source,
		$dependencies = array(),
		$version = false,
		$media = self::MEDIA_ALL
	) {
		$this->handle       = $handle;
		$this->source       = $this->normalize_source(
			$source,
			static::DEFAULT_EXTENSION
		);
		$this->dependencies = (array) $dependencies;
		$this->version      = $version;
		$this->media        = $media;
	}

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since %VERSION%
	 *
	 * @return Closure
	 */
	protected function get_register_closure() {
		return function () {
			if ( wp_script_is( $this->handle, 'registered' ) ) {
				return;
			}

			wp_register_style(
				$this->handle,
				$this->source,
				$this->dependencies,
				$this->version,
				$this->media
			);
		};
	}

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since %VERSION%
	 *
	 * @return Closure
	 */
	protected function get_enqueue_closure() {
		return function () {
			wp_enqueue_style( $this->handle );
		};
	}

	/**
	 * Get the dequeue closure to use.
	 *
	 * @since %VERSION%
	 *
	 * @return Closure
	 */
	protected function get_dequeue_closure() {
		return function () {
			wp_dequeue_style( $this->handle );
		};
	}
}
