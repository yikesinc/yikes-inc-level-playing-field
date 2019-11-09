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
 * Class ApplicationRepository
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class ApplicationRepository extends CustomPostTypeRepository {

	use PostFinder;
	use PostTypeApplication;

	/**
	 * Find the item with a given post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Application
	 * @throws InvalidPostID If the post for the requested ID was not found or is not the correct type.
	 */
	public function find( $id ) {
		return $this->find_item( $id );
	}

	/**
	 * Find all the published items.
	 *
	 * @since 1.0.0
	 *
	 * @return Application[]
	 */
	public function find_all() {
		return $this->find_all_items();
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return CustomPostTypeEntity
	 */
	protected function get_model_object( WP_Post $post ) {
		return new Application( $post );
	}
}
