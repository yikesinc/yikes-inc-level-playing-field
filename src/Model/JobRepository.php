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
use Yikes\LevelPlayingField\Query\JobQueryBuilder;

/**
 * Class JobRepository
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobRepository extends CustomPostTypeRepository {

	use PostFinder;
	use PostTypeJob;

	/**
	 * Find the Job with a given post ID.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return Job[]
	 */
	public function find_all() {
		return $this->find_all_items();
	}

	/**
	 * Find all active Jobs.
	 *
	 * @since 1.0.0
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
		$job_query = ( new JobQueryBuilder() )
			->posts_per_page( $limit )
			->orderby( $orderby )
			->where_job_active();

		if ( 'ASC' === strtoupper( $order ) ) {
			$job_query->order_ascending();
		}

		if ( ! empty( $exclude ) ) {
			$job_query->post__not_in( $exclude );
		}

		if ( ! empty( $cat_exclude_ids ) ) {
			$job_query->exclude_category_ids( $cat_exclude_ids );
		}

		$query = $job_query->get_query();
		$jobs  = [];
		foreach ( $query->posts as $post ) {
			$jobs[ $post->ID ] = $this->get_model_object( $post );
		}

		return $jobs;
	}

	/**
	 * Get the count of active Jobs.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function count_active() {
		$query = ( new JobQueryBuilder() )
			->for_count()
			->post_status( [ 'any' ] )
			->where_job_active()
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of Jobs using a particular Application.
	 *
	 * @since 1.0.0
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return int The count of jobs for the Application.
	 */
	public function get_count_for_application( $application_id ) {
		$query = ( new JobQueryBuilder() )
			->for_count()
			->where_application_id( $application_id )
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return Job
	 */
	protected function get_model_object( WP_Post $post ) {
		return new Job( $post );
	}
}
