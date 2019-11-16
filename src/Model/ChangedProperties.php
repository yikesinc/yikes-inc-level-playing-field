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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait ChangedProperties {

	/**
	 * Whether arbitrary properties are allowed.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $allow_arbitrary_properties = false;

	/**
	 * Changed properties for this object.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $changed = [];

	/**
	 * Properties for this object.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $properties = [];

	/**
	 * Whether the object properties have been loaded.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * Get the value of a property.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
