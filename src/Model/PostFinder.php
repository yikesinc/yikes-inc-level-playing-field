<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use WP_Post;
use Yikes\LevelPlayingField\Exception\InvalidPostID;

/**
 * Trait PostFinder
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait PostFinder {

	/**
	 * Find the item with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return CustomPostTypeEntity
	 * @throws InvalidPostID If the post for the requested ID was not found or is not the correct type.
	 */
	private function find_item( $id ) {
		$id   = intval( $id );
		$post = get_post( $id );
		if ( null === $post || $this->get_post_type() !== $post->post_type ) {
			$post_type = get_post_type_object( $this->get_post_type() );
			throw InvalidPostID::from_id( $id, $post_type->labels->singular_name );
		}

		return $this->get_model_object( $post );
	}

	/**
	 * Find all the published items.
	 *
	 * @since %VERSION%
	 *
	 * @return CustomPostTypeEntity[]
	 */
	private function find_all_items() {
		$items = [];
		$query = new \WP_Query( [
			'post_type'   => $this->get_post_type(),
			'post_status' => [ 'any' ],
		] );

		foreach ( $query->posts as $post ) {
			$items[ $post->ID ] = $this->get_model_object( $post );
		}

		return $items;
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_post_type();

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return CustomPostTypeEntity
	 */
	abstract protected function get_model_object( WP_Post $post );
}
