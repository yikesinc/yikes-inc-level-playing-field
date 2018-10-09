<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Model\JobRepository;

/**
 * Class Job
 *
 * This adds the "lpf_job" shortcode.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class Job extends BaseJobs {

	const TAG      = 'lpf_job';
	const VIEW_URI = 'views/job-page-job';

	/**
	 * Process the shortcode attributes and prepare rendering.
	 *
	 * This is overridden because get_context() could throw an exception if the
	 * job ID doesn't exist. When WP_DEBUG is enabled, any error message
	 * will be returned in place of the shortcode content.
	 *
	 * @since %VERSION%
	 *
	 * @param array|string $atts Attributes as passed to the shortcode.
	 *
	 * @return string Rendered HTML of the shortcode.
	 */
	public function process_shortcode( $atts ) {
		try {
			return parent::process_shortcode( $atts );
		} catch ( InvalidPostID $e ) {
			return WP_DEBUG ? esc_html( $e->getMessage() ) : '';
		}
	}

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_atts() {
		return [
			'id' => 0,
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
	protected function get_context( array $atts ) {
		$jobs_repository = new JobRepository();

		return [
			'job' => $jobs_repository->find( $atts['id'] ),
		];
	}
}
