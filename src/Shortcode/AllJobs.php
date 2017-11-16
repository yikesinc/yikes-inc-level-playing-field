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
class AllJobs extends BaseJobs {

	const TAG        = 'lpf_all_jobs';
	const VIEW_URI   = 'views/job-page';

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_atts() {
		return [
			'limit' => 10,
		];
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
	protected function get_context( $atts ) {
		$jobs_repository = new JobRepository();

		return [
			'jobs' => $jobs_repository->find_active( $atts['limit'] ),
		];
	}
}
