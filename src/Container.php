<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

/**
 * Class Container
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Container {

	/**
	 * The registered services for the container.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $services = array();

	/**
	 * Container constructor.
	 *
	 * @param array $services Services to register with the container.
	 */
	public function __construct( $services = null ) {
		$this->services = $services ?: array();
	}

	/**
	 * Get the services from the container.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_services() {
		return $this->services;
	}

	/**
	 * Add a service to the container.
	 *
	 * @since %VERSION%
	 *
	 * @param string $service Service class name.
	 */
	public function add_service( $service ) {
		$this->services[ $service ] = true;
	}

	/**
	 * Remove a service from the container.
	 *
	 * @since %VERSION%
	 *
	 * @param string $service Service class name.
	 */
	public function remove_service( $service ) {
		unset( $this->services[ $service ] );
	}
}
