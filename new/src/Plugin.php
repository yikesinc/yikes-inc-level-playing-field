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
	 * Instantiate a Plugin object.
	 *
	 * @since %VERSION%
	 *
	 * @param Container     $container
	 * @param AssetsHandler $assets_handler Optional. Instance of the assets handler to use.
	 */
	public function __construct( Container $container, AssetsHandler $assets_handler = null ) {
		$this->container      = $container;
		$this->assets_handler = $assets_handler ?: new AssetsHandler();
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * @since %VERSION%
	 *
	 * @throws Exception\InvalidService If a service is not valid.
	 */
	public function register() {
		add_action( 'plugins_loaded', array( $this, 'register_services' ) );
		add_action( 'init', array( $this, 'register_assets_handler' ) );
	}

	/**
	 * Register the individual services of this plugin.
	 *
	 * @since %VERSION%
	 *
	 * @throws Exception\InvalidService If a service is not valid.
	 */
	public function register_services() {
		$services = $this->get_services();
		$services = array_map( array( $this, 'instantiate_service' ), $services );
		array_walk( $services, function ( Service $service ) {
			$service->register();
		} );
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
			throw Exception\InvalidService::from_service( $class );
		}

		$service = new $class();

		if ( ! $service instanceof Service ) {
			throw Exception\InvalidService::from_service( $service );
		}

		if ( $service instanceof AssetsAware ) {
			$service->with_assets_handler( $this->assets_handler );
		}

		return $service;
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

		return $this->container->get_services();
	}
}
