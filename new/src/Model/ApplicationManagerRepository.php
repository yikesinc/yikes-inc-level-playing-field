<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationManagerCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;

/**
 * Class ApplicationManagerRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicationManagerRepository extends CustomPostTypeRepository {

	/**
	 * Find the Talk with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Application
	 * @throws InvalidPostID If the post for the requested ID was not found.
	 */
	public function find( $id ) {
		$post = get_post( $id );
		if ( null === $post ) {
			throw InvalidPostID::from_id( $id );
		}

		return new Application( $post );
	}

	/**
	 * Find all the published Talks.
	 *
	 * @since %VERSION%
	 *
	 * @return Application[]
	 */
	public function find_all() {
		$args  = array(
			'post_type'   => ApplicationManagerCPT::SLUG,
			'post_status' => array( 'active', 'inactive' ),
		);
		$query = new \WP_Query( $args );

		$jobs = array();
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = new Application( $post );
		}

		return $jobs;
	}
}
