<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use WP_Post;
use Yikes\LevelPlayingField\Exception\FailedToSavePost;
use Yikes\LevelPlayingField\Exception\InvalidProperty;

/**
 * Abstract class CustomPostTypeEntity.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class CustomPostTypeEntity implements Entity {

	/**
	 * Array of changed properties.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $changes = [];

	/**
	 * Whether this is a new Entity.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $new;

	/**
	 * WordPress post data representing the post.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * Whether post in this object has been changed.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $post_changed = false;

	/**
	 * Instantiate a CustomPostTypeEntity object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post Post object to instantiate a CustomPostTypeEntity model from.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
		$this->new  = 0 === $post->ID;

		// Initialize all property defaults for new objects.
		if ( $this->new ) {
			$this->load_all_lazy_properties();
		}
	}

	/**
	 * Return the entity ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int Entity ID.
	 */
	public function get_id() {
		return $this->post->ID;
	}

	/**
	 * Return the WP_Post object that represents this model.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Post WP_Post object representing this model.
	 */
	public function get_post_object() {
		return $this->post;
	}

	/**
	 * Get the post's title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Title of the post.
	 */
	public function get_title() {
		return $this->post->post_title;
	}

	/**
	 * Set the post's title.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title New title of the post.
	 */
	public function set_title( $title ) {
		$this->post_changed     = true;
		$this->post->post_title = $title;
	}

	/**
	 * Get the post's content.
	 *
	 * @since 1.0.0
	 *
	 * @return string Content of the post.
	 */
	public function get_content() {
		return $this->post->post_content;
	}

	/**
	 * Get the post's excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @return string Excerpt of the post.
	 */
	public function get_excerpt() {
		return wp_trim_excerpt( $this->post->post_excerpt, $this->post->ID );
	}

	/**
	 * Get the post's publish date, possibly formatted.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_format A date format usable in PHP's date function.
	 *
	 * @return string
	 */
	public function get_post_date( $date_format = '' ) {
		if ( ! empty( $date_format ) ) {
			return date( $date_format, strtotime( $this->post->post_date ) );
		}

		return $this->post->post_date;
	}

	/**
	 * Set the post's content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content New content of the post.
	 */
	public function set_content( $content ) {
		$this->post_changed       = true;
		$this->post->post_content = $content;
	}

	/**
	 * Magic getter method to fetch meta properties only when requested.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property Property that was requested.
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		try {
			return $this->get_property( $property );
		} catch ( InvalidProperty $e ) {
			$message = sprintf( 'Undefined property: %s::$%s', static::class, $property );
			trigger_error( esc_html( $message ), E_USER_NOTICE );

			return null;
		}
	}

	/**
	 * Persist the post object and post properties.
	 *
	 * @since 1.0.0
	 * @throws FailedToSavePost When there is a problem saving the post.
	 */
	public function persist() {
		$this->persist_post();
		$this->persist_properties();
	}

	/**
	 * Save changes to the post object.
	 *
	 * @since 1.0.0
	 * @return bool Whether the post was successfully updated.
	 * @throws FailedToSavePost When the post cannot be saved.
	 */
	public function persist_post() {
		if ( ! $this->post_changed && ! $this->new ) {
			return false;
		}

		$result = wp_insert_post( get_object_vars( $this->post ), true );
		if ( is_wp_error( $result ) ) {
			throw FailedToSavePost::from_type( $this->post->post_type, $result->get_error_message() );
		}

		if ( $this->new ) {
			$this->post = get_post( $result );
			$this->new  = false;
		}

		$this->post_changed = false;

		return $result;
	}

	/**
	 * Load all lazy properties.
	 *
	 * @since 1.0.0
	 */
	protected function load_all_lazy_properties() {
		foreach ( $this->get_lazy_properties() as $property => $default ) {
			if ( isset( $this->$property ) ) {
				continue;
			}

			$this->load_lazy_property( $property );
		}
	}

	/**
	 * Get the meta key for a given property.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The we want a meta key for.
	 *
	 * @return string The meta key.
	 */
	protected function get_meta_key( $property ) {
		return $property;
	}

	/**
	 * Register that a property has been changed.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property that has changed.
	 */
	protected function changed_property( $property ) {
		$this->changes[ $property ] = true;
	}

	/**
	 * Get a property from this object.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed The property value.
	 * @throws InvalidProperty When the property does not exist.
	 */
	public function get_property( $property ) {
		if ( $this->is_lazy_property( $property ) ) {
			$this->load_lazy_property( $property );
			return $this->{$property};
		}

		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}

		throw InvalidProperty::does_not_exist( $property );
	}

	/**
	 * Set a property for this Applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property to set.
	 * @param mixed  $value    The value of that property.
	 */
	public function set_property( $property, $value ) {
		$this->validate_can_modify_property( $property );
		$this->$property = $this->sanitize_property( $property, $value );
		$this->changed_property( $property );
	}

	/**
	 * Validate that a property can be modified.
	 *
	 * Certain properties are only able to be modified by internal methods.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property to validate.
	 *
	 * @throws InvalidProperty When the property cannot be modified.
	 */
	protected function validate_can_modify_property( $property ) {
		// Nothing to do in this base method. Override in child class.
	}

	/**
	 * Sanitize a property for this object.
	 *
	 * Must be overridden in a child class to do anything substantial.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property name to sanitize.
	 * @param mixed  $data     The data for the property.
	 *
	 * @return mixed The sanitized data.
	 */
	protected function sanitize_property( $property, $data ) {
		return $data;
	}

	/**
	 * Determine whether a given property is a lazy property.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property name.
	 *
	 * @return bool
	 */
	protected function is_lazy_property( $property ) {
		return array_key_exists( $property, $this->get_lazy_properties() );
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since 1.0.0
	 */
	abstract public function persist_properties();

	/**
	 * Return the list of lazily-loaded properties and their default values.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @param string $property Name of the property to load.
	 */
	abstract protected function load_lazy_property( $property );
}
