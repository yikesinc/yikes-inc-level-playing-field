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
use WP_Query;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;

/**
 * Class ApplicantRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantRepository extends CustomPostTypeRepository {

	use PostFinder;

	/**
	 * Find the Applicant with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Applicant
	 * @throws InvalidPostID If the post for the requested ID was not found or is not the correct type.
	 */
	public function find( $id ) {
		return $this->find_item( $id );
	}

	/**
	 * Find all the published Applicants.
	 *
	 * @since %VERSION%
	 *
	 * @return Applicant[]
	 */
	public function find_all() {
		return $this->find_all_items();
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicantCPT::SLUG;
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return CustomPostTypeEntity
	 */
	protected function get_model_object( WP_Post $post ) {
		return new Applicant( $post );
	}

	/**
	 * Get the count of applicants for a given Job ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of applicants for the Job.
	 */
	public function get_applicant_count_for_job( $job_id ) {
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
					'key'   => MetaLinks::JOB,
					'value' => $job_id,
				],
			],
		];

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of applicants for a given Application ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return int The count of applicants for the Application.
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

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}
}
