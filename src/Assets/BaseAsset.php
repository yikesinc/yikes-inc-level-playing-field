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
use Yikes\LevelPlayingField\Exception\InvalidURI;
use Yikes\LevelPlayingField\PluginHelper;

/**
 * Abstract class BaseAsset.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
abstract class BaseAsset implements Asset {

	use PluginHelper;

	const REGISTER_PRIORITY = 1;
	const ENQUEUE_PRIORITY  = 10;
	const DEQUEUE_PRIORITY  = 20;

	/**
	 * Handle of the asset.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $handle;

	/**
	 * Get the handle of the asset.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_handle() {
		return $this->handle;
	}

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->deferred_action( $this->get_register_action(), $this->get_register_closure(), static::REGISTER_PRIORITY );
	}

	/**
	 * Enqueue the asset.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		$this->deferred_action( $this->get_enqueue_action(), $this->get_enqueue_closure(), static::ENQUEUE_PRIORITY );
	}

	/**
	 * Dequeue the asset.
	 *
	 * @since 1.0.0
	 */
	public function dequeue() {
		$this->deferred_action( $this->get_dequeue_action(), $this->get_dequeue_closure(), static::DEQUEUE_PRIORITY );
	}

	/**
	 * Add a deferred action hook.
	 *
	 * If the action has already passed, the closure will be called directly.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $action   Deferred action to hook to.
	 * @param Closure $closure  Closure to attach to the action.
	 * @param int     $priority Optional. Priority to use. Defaults to 10.
	 */
	protected function deferred_action( $action, $closure, $priority = 10 ) {
		if ( did_action( $action ) ) {
			$closure();

			return;
		}

		add_action( $action, $closure, $priority );
	}

	/**
	 * Get the register action to use.
	 *
	 * @since 1.0.0
	 *
	 * @return string Register action to use.
	 */
	protected function get_register_action() {
		return $this->get_enqueue_action();
	}

	/**
	 * Get the enqueue action to use.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enqueue action name.
	 */
	protected function get_enqueue_action() {
		return is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';
	}

	/**
	 * Get the dequeue action to use.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enqueue action name.
	 */
	protected function get_dequeue_action() {
		return is_admin() ? 'admin_print_scripts' : 'wp_print_scripts';
	}

	/**
	 * Normalize the source URI.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri       Source URI to normalize.
	 * @param string $extension Default extension to use.
	 *
	 * @return string Normalized source URI.
	 */
	protected function normalize_source( $uri, $extension ) {
		$uri  = $this->check_extension( $uri, $extension );
		$path = trailingslashit( $this->get_root_dir() ) . $uri;
		$uri  = $this->get_plugin_url( $uri );

		return $this->check_for_minified_asset( $uri, $path, $extension );
	}

	/**
	 * Return the URI of the minified asset if it is readable and
	 * `SCRIPT_DEBUG` is not set.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri       Source URI.
	 * @param string $path      Source path.
	 * @param string $extension Default extension to use.
	 *
	 * @return string URI of the asset to use.
	 * @throws InvalidURI When the file specified by $path isn't readable.
	 */
	protected function check_for_minified_asset( $uri, $path, $extension ) {
		$debug         = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$minified_uri  = str_replace( ".$extension", ".min.{$extension}", $uri );
		$minified_path = str_replace( ".$extension", ".min.{$extension}", $path );

		// If both the regular and minified path aren't readable, that might mean that build scripts need to run.
		if ( ! is_readable( $path ) && ! is_readable( $minified_path ) ) {
			throw InvalidURI::from_asset_path( $path );
		}

		// If we're not in debug mode and we have a minified asset, or we're in a debug mode and we don't have an unminified asset but we have a minified asset, return the minified.
		return ! $debug && is_readable( $minified_path ) || $debug && ! is_readable( $path ) && is_readable( $minified_path ) ? $minified_uri : $uri;
	}

	/**
	 * Check that the URI has the correct extension.
	 *
	 * Optionally adds the extension if none was detected.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri       URI to check the extension of.
	 * @param string $extension Extension to use.
	 *
	 * @return string URI with correct extension.
	 */
	public function check_extension( $uri, $extension ) {
		$detected_extension = pathinfo( $uri, PATHINFO_EXTENSION );

		if ( $extension !== $detected_extension ) {
			$uri .= '.' . $extension;
		}

		return $uri;
	}

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	abstract protected function get_register_closure();

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	abstract protected function get_enqueue_closure();

	/**
	 * Get the dequeue closure to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	abstract protected function get_dequeue_closure();
}
