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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait PostFinder {

	/**
	 * Find the item with a given post ID.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return CustomPostTypeEntity[]
	 */
	private function find_all_items() {
		$ids   = $this->find_all_item_ids();
		$items = [];
		foreach ( $ids as $id ) {
			$items[ $id ] = $this->get_model_object( get_post( $id ) );
		}

		return $items;
	}

	/**
	 * Find all item IDs.
	 *
	 * @since 1.0.0
	 * @return int[] Array of post IDs.
	 */
	public function find_all_item_ids() {
		/**
		 * Filter the posts_per_page value.
		 *
		 * @param int    $posts_per_page The posts per page passed to WP_Query.
		 * @param string $post_type      The current post type.
		 */
		$posts_per_page = intval( apply_filters( 'lpf_posts_per_page', 100, $this->get_post_type() ) );

		// If -1 was passed, reset to the default.
		if ( -1 === $posts_per_page ) {
			$posts_per_page = 100;
			_doing_it_wrong( __METHOD__, esc_html( "Don't use unlimited queries for {$this->get_post_type()}" ), null );
		}

		$query = new \WP_Query( [
			'post_type'      => $this->get_post_type(),
			'post_status'    => [ 'any' ],
			'fields'         => 'ids',
			'posts_per_page' => $posts_per_page,
		] );

		return $query->posts;
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function get_post_type();

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return CustomPostTypeEntity
	 */
	abstract protected function get_model_object( WP_Post $post );
}
