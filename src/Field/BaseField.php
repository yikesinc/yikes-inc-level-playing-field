<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Exception\InvalidField;

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
	 * The pattern used for matching an field's ID.
	 *
	 * @link https://regex101.com/r/ZTgsNa/1
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $id_pattern = '#^([\w-]+)(\[(\d+)?\])?(?:\[([\w-]+)\])?#';

	/**
	 * BaseField constructor.
	 *
	 * @param string $id       The field ID.
	 * @param string $label    The field label.
	 * @param array  $classes  Array of field classes.
	 * @param bool   $required Whether the field is required.
	 *
	 * @throws InvalidField When the provided ID is invalid.
	 */
	public function __construct( $id, $label, array $classes, $required = true ) {
		$this->id       = $id;
		$this->label    = $label;
		$this->classes  = $classes;
		$this->required = (bool) $required;
		$this->validate_id();
	}

	/**
	 * Ensure we have a valid ID for the field.
	 *
	 * An ID is valid when it is a single word, or when it contains a single-depth array.
	 * Examples of valid IDs:
	 *
	 * foo
	 * foo[bar]
	 * foo_bar_baz
	 * foo-bar-baz
	 *
	 * Examples of invalid IDs:
	 *
	 * foo bar baz
	 * foo[bar][baz]
	 * foo[bar[baz]]
	 *
	 * @since %VERSION%
	 *
	 * @throws InvalidField When the provided ID is invalid for a form field.
	 */
	protected function validate_id() {
		// Make sure we match the pattern as a whole.
		if ( ! preg_match( $this->id_pattern, $this->id, $matches ) ) {
			throw InvalidField::invalid_id( $this->id );
		}

		// Make sure we matched the entire ID string.
		if ( $matches[0] !== $this->id ) {
			throw InvalidField::invalid_id( $this->id );
		}
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

		echo join( ' ', $pieces ), ' '; // XSS ok.
	}

	/**
	 * Render the required attribute.
	 *
	 * @since %VERSION%
	 */
	protected function render_required() {
		if ( $this->required ) {
			echo 'required="required" ';
		}
	}

	/**
	 * Get the raw submitted value.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	protected function get_raw_value() {
		preg_match( $this->id_pattern, $this->id, $m );
		return ! isset( $m[2] )
			? ( isset( $_POST[ $m[1] ] ) ? $_POST[ $m[1] ] : '' )
			: ( isset( $_POST[ $m[1] ][ $m[2] ] ) ? $_POST[ $m[1] ][ $m[2] ] : '' );
	}
}
