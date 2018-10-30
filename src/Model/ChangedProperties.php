<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Trait ChangedProperties
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait ChangedProperties {

	/**
	 * Whether arbitrary properties are allowed.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $allow_arbitrary_properties = false;

	/**
	 * Changed properties for this object.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $changed = [];

	/**
	 * Properties for this object.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $properties = [];

	/**
	 * Whether the object properties have been loaded.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * Get the value of a property.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed The property value.
	 */
	protected function get_property( $property ) {
		$value = null;

		if ( array_key_exists( $property, $this->properties ) ) {
			$value = array_key_exists( $property, $this->changed )
				? $this->changed[ $property ]
				: $this->properties[ $property ];
		}

		return $value;
	}

	/**
	 * Set the value of a property.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property to set.
	 * @param mixed  $value    The property value.
	 */
	protected function set_property( $property, $value ) {
		if ( ! $this->allow_arbitrary_properties && ! array_key_exists( $property, $this->properties ) ) {
			return;
		}

		if ( true === $this->loaded ) {
			if ( $value !== $this->properties[ $property ] || array_key_exists( $property, $this->changed ) ) {
				$this->changed[ $property ] = $value;
			}
		} else {
			$this->properties[ $property ] = $value;
		}
	}
}
