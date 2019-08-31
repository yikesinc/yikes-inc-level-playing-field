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
use Yikes\LevelPlayingField\Taxonomy\JobStatus;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;

/**
 * Class JobRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class JobRepository extends CustomPostTypeRepository {

	use PostFinder;
	use PostTypeJob;

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
	 * @param int    $limit           The maximum number of jobs to retrieve.
	 * @param string $orderby         The field for ordering.
	 * @param string $order           The order direction.
	 * @param mixed  $exclude         Either an array or a string of post IDs to exclude. If passed in as a string, turn it into an array by exploding the string at each comma.
	 * @param mixed  $cat_exclude_ids Either an array or a string of category IDs to exclude. If passed in as a string, turn it into an array by exploding the string at each comma.
	 *
	 * @return Job[]
	 */
	public function find_active( $limit = 10, $orderby = 'title', $order = 'ASC', $exclude = [], $cat_exclude_ids = [] ) {
		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => [ 'publish' ],
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'order'          => $order,
			'tax_query'      => [
				$this->get_active_job_status_tax_query(),
			],
		];

		if ( ! empty( $exclude ) ) {
			$args['post__not_in'] = is_array( $exclude ) ? $exclude : explode( ',', $exclude );
		}

		if ( ! empty( $ids ) ) {
			$args['tax_query'][] = $this->get_job_category_exclude_tax_query( $cat_exclude_ids );
		}

		$query = new \WP_Query( $args );

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
				$this->get_active_job_status_tax_query(),
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
				$this->get_application_meta_query( $application_id ),
			],
		];

		$query = new \WP_Query( $args );

		return absint( $query->found_posts );
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

	/**
	 * Get the tax query array for Jobs with Active status.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	private function get_active_job_status_tax_query() {
		return [
			'taxonomy' => JobStatus::SLUG,
			'field'    => 'slug',
			'terms'    => 'active',
		];
	}

	/**
	 * Get the tax query array for excluding the specified categories.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $ids An array or string of IDs to exclude.
	 *
	 * @return array
	 */
	private function get_job_category_exclude_tax_query( $ids = [] ) {
		return [
			'taxonomy' => JobCategory::SLUG,
			'field'    => 'term_id',
			'terms'    => is_array( $ids ) ? $ids : explode( ',', $ids ),
			'operator' => 'NOT IN',
		];
	}
}
