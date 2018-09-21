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

/**
 * Abstract class CustomPostTypeEntity.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class CustomPostTypeEntity implements Entity {

	/**
	 * WordPress post data representing the post.
	 *
	 * @since %VERSION%
	 *
	 * @var WP_Post
	 */
	protected $post;

	/**
	 * Whether post in this object has been changed.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $post_changed = false;

	/**
	 * Instantiate a CustomPostTypeEntity object.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post Post object to instantiate a CustomPostTypeEntity model from.
	 */
	public function __construct( WP_Post $post ) {
		$this->post = $post;
	}

	/**
	 * Return the entity ID.
	 *
	 * @since %VERSION%
	 *
	 * @return int Entity ID.
	 */
	public function get_id() {
		return $this->post->ID;
	}

	/**
	 * Return the WP_Post object that represents this model.
	 *
	 * @since %VERSION%
	 *
	 * @return WP_Post WP_Post object representing this model.
	 */
	public function get_post_object() {
		return $this->post;
	}

	/**
	 * Get the post's title.
	 *
	 * @since %VERSION%
	 *
	 * @return string Title of the post.
	 */
	public function get_title() {
		return $this->post->post_title;
	}

	/**
	 * Set the post's title.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @return string Content of the post.
	 */
	public function get_content() {
		return $this->post->post_content;
	}

	/**
	 * Set the post's content.
	 *
	 * @since %VERSION%
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

		$message = sprintf( 'Undefined property: %s::$%s', static::class, $property );
		trigger_error( esc_html( $message ), E_USER_NOTICE );

		return null;
	}

	/**
	 * Persist the post object and post properties.
	 *
	 * @since %VERSION%
	 */
	public function persist() {
		$this->persist_post();
		$this->persist_properties();
	}

	/**
	 * Save changes to the post object.
	 *
	 * @since %VERSION%
	 * @return bool Whether the post was successfully updated.
	 */
	public function persist_post() {
		if ( ! $this->post_changed ) {
			return false;
		}

		$result = (bool) wp_insert_post( get_object_vars( $this->post ) );
		if ( $result ) {
			$this->post_changed = false;
		}

		return $result;
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	abstract public function persist_properties();

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
