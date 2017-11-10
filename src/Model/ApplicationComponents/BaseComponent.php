<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\ApplicationComponents;

use Yikes\LevelPlayingField\Model\CustomPostTypeEntity;

/**
 * Class BaseComponent
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseComponent implements Component {

	/**
	 * Custom post entity.
	 *
	 * @since %VERSION%
	 * @var CustomPostTypeEntity
	 */
	protected $entity;

	/**
	 * BaseComponent constructor.
	 *
	 * @param CustomPostTypeEntity $entity
	 */
	public function __construct( CustomPostTypeEntity $entity ) {
		$this->entity = $entity;
	}

	/**
	 * Get the ID for this component.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function get_id() {
		return $this->entity->get_id();
	}

	/**
	 * Magic getter method to fetch meta properties only when requested.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property Property that was requested.
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		if ( array_key_exists( $property, $this->get_lazy_properties() ) ) {
			$this->load_lazy_property( $property );

			return $this->{$property};
		}

		$message = sprintf( 'Undefined property: %s::$%s', get_class(), $property );
		trigger_error( $message, E_USER_NOTICE );

		return null;
	}

	/**
	 * Return the list of lazily-loaded properties and their default values.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	abstract protected function get_lazy_properties();

	/**
	 * Load a lazily-loaded property.
	 *
	 * After this process, the loaded property should be set within the
	 * object's state, otherwise the load procedure might be triggered multiple
	 * times.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property Name of the property to load.
	 */
	abstract protected function load_lazy_property( $property );
}
