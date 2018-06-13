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
	 * Whether the field is required.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $required;

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
	 * @param string $id       The field ID.
	 * @param string $label    The field label.
	 * @param array  $classes  Array of field classes.
	 * @param bool   $required Whether the field is required.
	 */
	public function __construct( $id, $label, array $classes, $required = true ) {
		$this->id       = $id;
		$this->label    = $label;
		$this->classes  = $classes;
		$this->required = (bool) $required;
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

	/**
	 * Render any data attributes for this field.
	 *
	 * @since %VERSION%
	 */
	protected function render_data_attributes() {
		if ( empty( $this->data ) ) {
			return;
		}

		$pieces = [];
		foreach ( $this->data as $key => $datum ) {
			$key      = strtolower( str_replace( [ '_', ' ' ], '-', $key ) );
			$pieces[] = sprintf( 'data-%s="%s"', esc_html( $key ), esc_attr( $datum ) );
		}

		echo join( ' ', $pieces ); // XSS ok.
	}

	/**
	 * Render the required attribute.
	 *
	 * @since %VERSION%
	 */
	protected function render_required() {
		if ( $this->required ) {
			echo 'required="required"';
		}
	}
}
