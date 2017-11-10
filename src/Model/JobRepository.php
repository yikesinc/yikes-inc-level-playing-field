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
 * Class JobRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobRepository extends CustomPostTypeRepository {

	/**
	 * Find the Job with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Job
	 * @throws InvalidPostID If the post for the requested ID was not found.
	 */
	public function find( $id ) {
		$post = get_post( $id );
		if ( null === $post ) {
			throw InvalidPostID::from_id( $id );
		}

		return new Job( $post );
	}

	/**
	 * Find all the published Jobs.
	 *
	 * @since %VERSION%
	 *
	 * @return Job[]
	 */
	public function find_all() {
		$args  = [
			'post_type'   => JobManagerCPT::SLUG,
			'post_status' => [ 'any' ],
		];
		$query = new \WP_Query( $args );

		$jobs = [];
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = new Job( $post );
		}

		return $jobs;
	}

	/**
	 * Find all active Jobs.
	 *
	 * @since %VERSION%
	 *
	 * @param int $limit The maximum number of jobs to retrieve.
	 *
	 * @return Job[]
	 */
	public function find_active( $limit = 10 ) {
		$query = new \WP_Query( [
			'post_type'      => JobManagerCPT::SLUG,
			'post_status'    => [ 'publish' ],
			'posts_per_page' => $limit,
			'orderby'        => 'title',
			'tax_query'      => [
				[
					'taxonomy' => JobStatus::SLUG,
					'field'    => 'slug',
					'terms'    => 'active',
				],
			],
		] );

		$jobs = [];
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = new Job( $post );
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
		$args = [
			'post_type'              => JobManagerCPT::SLUG,
			'post_status'            => [ 'any' ],
			// Limit posts per page, because WP_Query will still tell us the total.
			'posts_per_page'         => 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
			'tax_query'              => [
				[
					'taxonomy' => JobStatus::SLUG,
					'field'    => 'slug',
					'terms'    => 'active',
				],
			],
		];

		$query = new \WP_Query( $args );

		return absint( $query->found_posts );
	}
}
