<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;


use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;

/**
 * Class JobManagerRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobManagerRepository extends CustomPostTypeRepository {

	/**
	 * Find the Talk with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return JobManager
	 * @throws InvalidPostID If the post for the requested ID was not found.
	 */
	public function find( $id ) {
		$post = get_post( $id );
		if ( null === $post ) {
			throw InvalidPostID::from_id( $id );
		}

		return new JobManager( $post );
	}

	/**
	 * Find all the published Talks.
	 *
	 * @since %VERSION%
	 *
	 * @return JobManager[]
	 */
	public function find_all() {
		$args  = array(
			'post_type'   => JobManagerCPT::SLUG,
			'post_status' => array( 'active', 'inactive' ),
		);
		$query = new \WP_Query( $args );

		$jobs = array();
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = new JobManager( $post );
		}

		return $jobs;
	}
}
