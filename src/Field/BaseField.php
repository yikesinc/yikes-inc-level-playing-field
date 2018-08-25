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
 *
 * @property Field parent The Parent field object.
 */
abstract class BaseField implements Field {

	/**
	 * The filter for sanitizing.
	 *
	 * Override in child classes to use a different sanitize filter.
	 *
	 * @see http://php.net/manual/en/filter.filters.sanitize.php.
	 */
	const SANITIZE = FILTER_SANITIZE_STRING;

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
	 * Whether the field is repeatable.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $repeatable = false;

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
	 * The parent field.
	 *
	 * @since %VERSION%
	 * @var Field
	 */
	protected $parent;

	/**
	 * The pattern used for matching an field's ID.
	 *
	 * @link  https://regex101.com/r/ZTgsNa/1
	 * @since %VERSION%
	 * @var string
	 */
	protected $id_pattern = '#^([\w-]+)(?:\[(\d+)?\])?(?:\[([\w-]+)\])?#';

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
	 * Maybe return data from inaccessible members.
	 *
	 * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
	 *
	 * @param string $name The property to retrieve.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'parent':
				if ( ! isset( $this->parent ) ) {
					$this->parent = new NullParent();
				}

				return $this->parent;

			default:
				return null;
		}
	}

	/**
	 * Whether this field is repeatable.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_repeatable() {
		return $this->repeatable;
	}

	/**
	 * Set the parent field object for this field.
	 *
	 * @since %VERSION%
	 *
	 * @param Field $field The parent field object.
	 */
	public function set_parent( Field $field ) {
		$this->parent = $field;
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
	 * Validate the submission for the given field.
	 *
	 * @since %VERSION%
	 *
	 * @param array $data The submission data to use for validation.
	 *
	 * @return mixed The validated value.
	 * @throws InvalidField When the submission isn't valid.
	 */
	public function validate_submission( $data ) {
		$raw = $this->get_raw_value( $data );
		if ( empty( $raw ) && $this->required ) {
			throw InvalidField::field_required( $this->label );
		}

		$filtered = $this->sanitize_value( $raw );
		if ( false === $filtered || empty( $filtered ) ) {
			throw InvalidField::value_invalid( $this->label );
		}

		return $filtered;
	}

	/**
	 * Get the raw submitted value.
	 *
	 * @since %VERSION%
	 *
	 * @param array $data Array where the raw value can be obtained.
	 *
	 * @return mixed
	 */
	protected function get_raw_value( $data ) {
		preg_match( $this->id_pattern, $this->id, $m );
		return ! isset( $m[2] )
			? ( isset( $data[ $m[1] ] ) ? $data[ $m[1] ] : '' )
			: ( isset( $data[ $m[1] ][ $m[2] ] ) ? $data[ $m[1] ][ $m[2] ] : '' );
	}

	/**
	 * Sanitize a submitted value.
	 *
	 * @since %VERSION%
	 *
	 * @param string $raw The raw value for the field.
	 *
	 * @return mixed
	 */
	protected function sanitize_value( $raw ) {
		return filter_var( $raw, static::SANITIZE, $this->get_filter_options() );
	}

	/**
	 * Return options to use when sanitizing a submitted value.
	 *
	 * @link  http://php.net/manual/en/function.filter-var.php
	 * @see   filter_var()
	 * @since %VERSION%
	 * @return null|callable|int|array Return null for no options, a callable, an int when using filter flags, or an
	 *                                 array when using additional options for the filter.
	 */
	protected function get_filter_options() {
		return null;
	}
}
