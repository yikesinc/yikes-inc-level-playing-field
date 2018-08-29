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
use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class JobRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class JobRepository extends CustomPostTypeRepository {

	use PostFinder;

	/**
	 * Find the Job with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Job
	 * @throws InvalidPostID If the post for the requested ID was not found or is not the correct type.
	 */
	public function find( $id ) {
		return $this->find_item( $id );
	}

	/**
	 * Find all the published Jobs.
	 *
	 * @since %VERSION%
	 *
	 * @return Job[]
	 */
	public function find_all() {
		return $this->find_all_items();
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
			'post_type'      => $this->get_post_type(),
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
			$jobs[ $post->ID ] = $this->get_model_object( $post );
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
			'post_type'              => $this->get_post_type(),
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

	/**
	 * Get the count of Jobs using a particular Application.
	 *
	 * @since %VERSION%
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return int The count of jobs for the Application.
	 */
	public function get_count_for_application( $application_id ) {
		$args = [
			'post_type'              => $this->get_post_type(),
			'post_status'            => [ 'any' ],
			// Limit posts per page, because WP_Query will still tell us the total.
			'posts_per_page'         => 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
			'meta_query'             => [
				[
					'key'   => MetaLinks::APPLICATION,
					'value' => $application_id,
				],
			],
		];

		$query = new \WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return JobManagerCPT::SLUG;
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return Job
	 */
	protected function get_model_object( WP_Post $post ) {
		return new Job( $post );
	}
}
