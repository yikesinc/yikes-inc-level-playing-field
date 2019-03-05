<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsHandler;
use Yikes\LevelPlayingField\Exception\InvalidClass;

/**
 * Class Plugin.
 *
 * Main plugin controller class that hooks the plugin's functionality into the
 * WordPress request lifecycle.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class Plugin implements Registerable {

	const VERSION = '1.0.0';

	/**
	 * Assets handler instance.
	 *
	 * @since %VERSION%
	 *
	 * @var AssetsHandler
	 */
	protected $assets_handler;

	/**
	 * Container instance.
	 *
	 * @since %VERSION%
	 * @var Container
	 */
	protected $container;

	/**
	 * The root plugin directory.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $plugin_root;

	/**
	 * Instantiate a Plugin object.
	 *
	 * @since %VERSION%
	 *
	 * @param Container     $container      The container object.
	 * @param AssetsHandler $assets_handler Optional. Instance of the assets handler to use.
	 * @param string        $plugin_root    The root directory of the plugin.
	 */
	public function __construct( Container $container, AssetsHandler $assets_handler = null, $plugin_root = '' ) {
		$this->container      = $container;
		$this->assets_handler = $assets_handler ?: new AssetsHandler();
		$this->plugin_root    = $plugin_root ?: dirname( __DIR__ );
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'plugins_loaded', [ $this, 'register_services' ], 20 );
		add_action( 'init', [ $this, 'register_assets_handler' ] );
	}

	/**
	 * Register the individual services of this plugin.
	 *
	 * @since %VERSION%
	 */
	public function register_services() {
		$services = $this->get_services();
		$services = array_map( [ $this, 'instantiate_service' ], $services );
		array_walk( $services, function( Service $service ) {
			$service->register();
		} );
	}

	/**
	 * Get the list of services to register.
	 *
	 * @since %VERSION%
	 *
	 * @return string[] Array of fully qualified class names.
	 */
	protected function get_services() {
		/**
		 * Fires right before the Level Playing Field services are retrieved.
		 *
		 * @param Container $container The services container object.
		 */
		do_action( 'lpf_pre_get_services', $this->container );

		return array_keys( $this->container->get_services() );
	}

	/**
	 * Register the assets handler.
	 *
	 * @since %VERSION%
	 */
	public function register_assets_handler() {
		$this->assets_handler->register();
	}

	/**
	 * Return the instance of the assets handler in use.
	 *
	 * @since %VERSION%
	 *
	 * @return AssetsHandler
	 */
	public function get_assets_handler() {
		return $this->assets_handler;
	}

	/**
	 * Get the root directory for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_root() {
		return $this->plugin_root;
	}

	/**
	 * Instantiate a single service.
	 *
	 * @since %VERSION%
	 *
	 * @param string $class Service class to instantiate.
	 *
	 * @return Service
	 * @throws Exception\InvalidService If the service is not valid.
	 */
	protected function instantiate_service( $class ) {
		if ( ! class_exists( $class ) ) {
			throw InvalidClass::not_found( $class );
		}

		$service = new $class();

		if ( ! ( $service instanceof Service ) ) {
			throw InvalidClass::from_interface( $class, Service::class );
		}

		if ( $service instanceof AssetsAware ) {
			$service->with_assets_handler( $this->assets_handler );
		}

		return $service;
	}
}
