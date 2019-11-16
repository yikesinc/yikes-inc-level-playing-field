<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;

/**
 * Class AllJobs
 *
 * This creates the "lpf_all_jobs" shortcode.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class AllJobs extends BaseJobs {

	const TAG                      = 'lpf_all_jobs';
	const VIEW_URI                 = 'views/job-page';
	const JOBS_LIST_PARTIAL        = 'views/jobs';
	const JOBS_BY_CAT_LIST_PARTIAL = 'views/jobs-by-cat';
	const JOBS_LOOP_PARTIAL        = 'views/jobs-loop';

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_default_atts() {
		$default_atts = [
			'limit'                   => 10,
			'show_desc'               => false,
			'desc_type'               => 'excerpt',
			'show_details'            => false,
			'show_application_button' => false,
			'order'                   => 'asc',
			'orderby'                 => 'title',
			'grouped_by_cat'          => false,
			'exclude'                 => [],
			'cat_exclude_ids'         => [],
			'details_text'            => __( 'Job Details', 'level-playing-field' ),
			'job_type_text'           => __( 'Job Type:', 'level-playing-field' ),
			'location_text'           => __( 'Location:', 'level-playing-field' ),
			'button_text'             => __( 'Apply', 'level-playing-field' ),
			'remote_location_text'    => _x( 'Remote - employees work from their location of choice. ', 'Description of the job location', 'level-playing-field' ),
		];

		/**
		 * Filter the default attributes for the job listings shortcode.
		 *
		 * @since 1.0.0
		 *
		 * @param array $default_atts Array of shortcode attributes.
		 *
		 * @return array The shortcode attributes, maybe filtered.
		 */
		return apply_filters( 'lpf_job_listings_default_attributes', $default_atts );
	}

	/**
	 * Get the context to pass onto the view.
	 *
	 * Override to provide data to the view that is not part of the shortcode
	 * attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( array $atts ) {
		$jobs_repository = new JobRepository();
		$jobs            = $jobs_repository->find_active( $atts['limit'], $atts['orderby'], $atts['order'], $atts['exclude'], $atts['cat_exclude_ids'] );
		$job_cats        = [];
		$jobs_by_cat     = [];

		$atts['grouped_by_cat'] = filter_var( $atts['grouped_by_cat'], FILTER_VALIDATE_BOOLEAN );

		if ( $atts['grouped_by_cat'] ) {

			// Loop through each job and determine job category.
			foreach ( $jobs as $job ) {
				$job_terms = get_the_terms( $job->get_post_object(), JobCategory::SLUG );

				// If job does not have a term, skip.
				if ( ! $job_terms ) {
					continue;
				}

				// Otherwise, set up category variables for proper rendering.
				foreach ( $job_terms as $job_term ) {
					$job_cats[ $job_term->term_id ] = $job_term->name;
					if ( isset( $jobs_by_cat[ $job_term->term_id ] ) ) {
						$jobs_by_cat[ $job_term->term_id ][] = $job;
					} else {
						$jobs_by_cat[ $job_term->term_id ] = [ $job ];
					}
				}
			}
		}

		return [
			'jobs'     => $atts['grouped_by_cat'] ? $jobs_by_cat : $jobs,
			'job_cats' => $job_cats,
			'partials' => [
				'jobs_list'             => static::JOBS_LIST_PARTIAL,
				'jobs_by_category_list' => static::JOBS_BY_CAT_LIST_PARTIAL,
				'jobs_loop'             => static::JOBS_LOOP_PARTIAL,
				'job_details'           => static::JOB_DETAILS_PARTIAL,
				'job_apply_button'      => static::JOB_APPLY_PARTIAL,
			],
		];
	}
}
