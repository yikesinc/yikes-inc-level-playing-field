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
use Yikes\LevelPlayingField\AdminPage\SupportPage;
use Yikes\LevelPlayingField\AdminPage\SettingsPage;
use Yikes\LevelPlayingField\AdminPage\GoProPage;
use Yikes\LevelPlayingField\CustomPostType\JobManager;

/**
 * Class Plugin.
 *
 * Main plugin controller class that hooks the plugin's functionality into the
 * WordPress request lifecycle.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class Plugin implements Registerable {

	use PluginHelper;

	const VERSION = '1.0.0';

	/**
	 * Assets handler instance.
	 *
	 * @since 1.0.0
	 *
	 * @var AssetsHandler
	 */
	protected $assets_handler;

	/**
	 * Container instance.
	 *
	 * @since 1.0.0
	 * @var Container
	 */
	protected $container;

	/**
	 * Array of registered services.
	 *
	 * @since 1.0.0
	 * @var Service[]
	 */
	private $services = [];

	/**
	 * Instantiate a Plugin object.
	 *
	 * @since 1.0.0
	 *
	 * @param Container     $container      The container object.
	 * @param AssetsHandler $assets_handler Optional. Instance of the assets handler to use.
	 */
	public function __construct( Container $container, AssetsHandler $assets_handler = null ) {
		$this->container      = $container;
		$this->assets_handler = $assets_handler ?: new AssetsHandler();
	}

	/**
	 * Register the plugin with the WordPress system.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_assets_handler' ] );
		add_action( "plugin_action_links_{$this->get_basename()}", [ $this, 'plugin_action_links' ] );
		register_activation_hook( $this->get_main_file(), [ $this, 'activate' ] );
		register_deactivation_hook( $this->get_main_file(), [ $this, 'deactivate' ] );

		add_action( 'plugins_loaded', [ $this, 'register_services' ], 20 );
		add_action( 'plugins_loaded', function() {
			/**
			 * Fires after the Level Playing Field plugin has been loaded.
			 *
			 * This runs on the plugins_loaded hook so that other plugins have a chance to hook
			 * in. It also runs on an early priority of 0 so that other plugins hooking in have
			 * a chance to modify our early filters.
			 *
			 * @since 1.0.0
			 *
			 * @param Plugin $lpf_plugin The main plugin instance.
			 */
			do_action( 'lpf_loaded', $this );
		}, 0 );
	}

	/**
	 * Run activation logic.
	 */
	public function activate() {
		$this->register_services();
		foreach ( $this->services as $service ) {
			if ( $service instanceof Activateable ) {
				$service->activate();
			}
		}

		flush_rewrite_rules();
	}

	/**
	 * Run deactivation logic.
	 */
	public function deactivate() {
		foreach ( $this->services as $service ) {
			if ( $service instanceof Deactivateable ) {
				$service->deactivate();
			}
		}
	}

	/**
	 * Register the individual services of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function register_services() {
		$services = $this->get_services();
		$services = array_map( [ $this, 'instantiate_service' ], $services );
		array_walk( $services, function( Service $service ) {
			$service->register();
		} );
		$this->services = $services;
	}

	/**
	 * Get the list of services to register.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public function register_assets_handler() {
		$this->assets_handler->register();
	}

	/**
	 * Return the instance of the assets handler in use.
	 *
	 * @since 1.0.0
	 *
	 * @return AssetsHandler
	 */
	public function get_assets_handler() {
		return $this->assets_handler;
	}

	/**
	 * Instantiate a single service.
	 *
	 * @since 1.0.0
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

	/**
	 * Add custom links to the plugins page LPF item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links The default action links.
	 *
	 * @return array The action links, extended.
	 */
	public function plugin_action_links( $links ) {
		// @todo: move this into a separate class.
		$lpf_links = [
			SettingsPage::PAGE_SLUG => '<a href="' . esc_url( ( new SettingsPage() )->get_page_url() ) . '">' . esc_html__( 'Settings', 'level-playing-field' ) . '</a>',
			SupportPage::PAGE_SLUG  => '<a href="' . esc_url( ( new SupportPage() )->get_page_url() ) . '">' . esc_html__( 'Support', 'level-playing-field' ) . '</a>',
			JobManager::SLUG        => '<a href="' . esc_url( ( new JobManager() )->get_add_new_url() ) . '">' . esc_html__( 'Add a Job', 'level-playing-field' ) . '</a>',
			GoProPage::PAGE_SLUG    => '<a href="' . esc_url( ( new GoProPage() )->get_page_url() ) . '">' . esc_html__( 'Go Pro', 'level-playing-field' ) . '</a>',
		];
		return array_merge( $lpf_links, $links );
	}
}
