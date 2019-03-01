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
use Yikes\LevelPlayingField\Settings\Settings;
use Yikes\LevelPlayingField\Settings\SettingsFields;

/**
 * Class StyleAsset.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
final class StyleAsset extends BaseAsset {

	const MEDIA_ALL    = 'all';
	const MEDIA_PRINT  = 'print';
	const MEDIA_SCREEN = 'screen';
	const DEPENDENCIES = [];
	const VERSION      = false;
	const DISABLEABLE  = false;

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
	 * @var string[]
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
	 * Whether this asset can be disabled.
	 *
	 * @since %VERSION%
	 *
	 * @var string
	 */
	protected $disableable;

	/**
	 * Instantiate a StyleAsset object.
	 *
	 * @since %VERSION%
	 *
	 * @param string           $handle       Handle of the asset.
	 * @param string           $source       Source location of the asset.
	 * @param array            $dependencies Optional. Dependencies of the asset.
	 * @param string|bool|null $version      Optional. Version of the asset.
	 * @param string           $media        Media for which the asset is defined.
	 * @param bool             $disableable  Whether this script can be disabled.
	 */
	public function __construct(
		$handle,
		$source,
		$dependencies = [],
		$version = false,
		$media = self::MEDIA_ALL,
		$disableable = false
	) {
		$this->handle       = $handle;
		$this->source       = $this->normalize_source( $source, static::DEFAULT_EXTENSION );
		$this->dependencies = (array) $dependencies;
		$this->version      = $version;
		$this->media        = $media;
		$this->disableable  = $disableable;
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
			if ( wp_script_is( $this->handle, 'registered' ) || ( $this->disableable && ( new Settings() )->get_setting( SettingsFields::DISABLE_FRONT_END_CSS ) ) ) {
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
