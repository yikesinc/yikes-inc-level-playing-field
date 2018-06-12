<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;

/**
 * Class ApplicantRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantRepository extends CustomPostTypeRepository {

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
			'post_type'              => ApplicantCPT::SLUG,
			'post_status'            => [ 'any' ],
			// Limit posts per page, because WP_Query will still tell us the total.
			'posts_per_page'         => 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
			'meta_query'             => [
				[
					'key'   => '_job_id',
					'value' => $job_id,
				],
			],
		];

		$query = new \WP_Query( $args );

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
			'post_type'              => ApplicantCPT::SLUG,
			'post_status'            => [ 'any' ],
			// Limit posts per page, because WP_Query will still tell us the total.
			'posts_per_page'         => 1,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'fields'                 => 'ids',
			'meta_query'             => [
				[
					'key'   => '_application_id',
					'value' => $application_id,
				],
			],
		];

		$query = new \WP_Query( $args );

		return absint( $query->found_posts );
	}
}
