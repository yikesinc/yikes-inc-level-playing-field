<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class BaseField
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseField implements Field {

	/**
	 * The field ID.
	 *
	 * Used in HTML for id and name tags.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $id;

	/**
	 * The field label.
	 *
	 * Used inside a <label> element.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $label;

	/**
	 * Classes to apply to the field.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $classes;

	/**
	 * Data attributes for the field.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $data = [];

	/**
	 * BaseField constructor.
	 *
	 * @param string $id      The field ID.
	 * @param string $label   The field label.
	 * @param array  $classes Array of field classes.
	 */
	public function __construct( $id, $label, array $classes ) {
		$this->id      = $id;
		$this->label   = $label;
		$this->classes = $classes;
	}

	/**
	 * Add a data attribute to the field.
	 *
	 * @since %VERSION%
	 *
	 * @param string $key   The data key. Should NOT include data- prefix.
	 * @param string $value The data value.
	 */
	public function add_data( $key, $value ) {
		$this->data[ $key ] = $value;
	}
}
