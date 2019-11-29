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
use Yikes\LevelPlayingField\Settings\DisableFrontEndCss;

/**
 * Class StyleAsset.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
final class StyleAsset extends BaseAsset {

	const MEDIA_ALL    = 'all';
	const MEDIA_PRINT  = 'print';
	const MEDIA_SCREEN = 'screen';
	const DEPENDENCIES = [];

	const DEFAULT_EXTENSION = 'css';

	/**
	 * Source location of the asset.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $source;

	/**
	 * Dependencies of the asset.
	 *
	 * @since 1.0.0
	 *
	 * @var string[]
	 */
	protected $dependencies;

	/**
	 * Version of the asset.
	 *
	 * @since 1.0.0
	 *
	 * @var string|bool|null
	 */
	protected $version;

	/**
	 * Media for which the asset is defined.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $media;

	/**
	 * Whether this asset can be disabled.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $disableable;

	/**
	 * Instantiate a StyleAsset object.
	 *
	 * @since 1.0.0
	 *
	 * @param string           $handle       Handle of the asset.
	 * @param string           $source       Source location of the asset.
	 * @param array            $dependencies Optional. Dependencies of the asset.
	 * @param string|bool|null $version      Optional. Version of the asset.
	 * @param string           $media        Media for which the asset is defined.
	 */
	public function __construct(
		$handle,
		$source,
		$dependencies = self::DEPENDENCIES,
		$version = null,
		$media = self::MEDIA_ALL
	) {
		$this->handle       = $handle;
		$this->source       = $this->normalize_source( $source, static::DEFAULT_EXTENSION );
		$this->dependencies = (array) $dependencies;
		$this->version      = $version ?: $this->get_version();
		$this->media        = $media;
	}

	/**
	 * Whether the asset can be disabled.
	 *
	 * @since %VERSION%
	 *
	 * @param bool $disableable
	 *
	 * @return $this
	 */
	public function set_disableable( $disableable = true ) {
		$this->disableable = (bool) $disableable;
		return $this;
	}

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	protected function get_register_closure() {
		return function () {
			if ( wp_script_is( $this->handle, 'registered' ) ) {
				return;
			}

			if ( $this->is_disabled() ) {
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	protected function get_dequeue_closure() {
		return function () {
			wp_dequeue_style( $this->handle );
		};
	}

	/**
	 * Whether the current style is disabled.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function is_disabled() {
		if ( ! $this->disableable ) {
			return false;
		}

		$setting = ( new DisableFrontEndCss() )->get();
		return isset( $setting[ $this->handle ] ) && true === $setting[ $this->handle ];
	}
}
