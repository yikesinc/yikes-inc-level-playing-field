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
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

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
			'post_status' => array( 'any' ),
		);
		$query = new \WP_Query( $args );

		$jobs = array();
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = new JobManager( $post );
		}

		return $jobs;
	}

	/**
	 * Get the count of active Jobs.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function count_active() {
		$args = array(
			'post_type'              => JobManagerCPT::SLUG,
			'post_status'            => array( 'any' ),
			// Limit posts per page, because WP_Query will still tell us the total.
			'posts_per_page'         => 10,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
			'tax_query'              => array(
				array(
					'taxonomy' => JobStatus::SLUG,
					'field'    => 'slug',
					'terms'    => 'active',
				),
			),
		);

		$query = new \WP_Query( $args );

		return $query->post_count;
	}
}
