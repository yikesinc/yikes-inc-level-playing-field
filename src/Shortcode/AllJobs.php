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

/**
 * Class AllJobs
 *
 * This creates the "lpf_all_jobs" shortcode.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class AllJobs extends BaseJobs {

	const TAG      = 'lpf_all_jobs';
	const VIEW_URI = 'views/job-page';

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_default_atts() {
		$default_atts = [
			'limit'                   => 10,
			'show_desc'               => false,
			'desc_type'               => 'excerpt',
			'show_application_button' => false,
			'button_text'             => __( 'Apply', 'yikes-level-playing-field' ),
			'order'                   => 'asc',
			'orderby'                 => 'title',
			'exclude'                 => [],
			'cat_exclude_ids'         => [],
		];

		/**
		 * Filter the default attributes for the job listings shortcode.
		 *
		 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( array $atts ) {
		$jobs_repository = new JobRepository();

		return [
			'jobs' => $jobs_repository->find_active( $atts['limit'], $atts['orderby'], $atts['order'], $atts['exclude'], $atts['cat_exclude_ids'] ),
		];
	}
}
