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
 * @since   1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_default_atts() {
		$default_atts = [
			'id'                      => 0,
			'show_title'              => true,
			'show_description'        => true,
			'show_job_type'           => true,
			'show_location'           => true,
			'show_application_button' => true,
			'description_text'        => __( 'Description', 'level-playing-field' ),
			'details_text'            => __( 'Job Details', 'level-playing-field' ),
			'job_type_text'           => __( 'Job Type:', 'level-playing-field' ),
			'location_text'           => __( 'Location:', 'level-playing-field' ),
			'button_text'             => __( 'Apply', 'level-playing-field' ),
			'remote_location_text'    => _x( 'Remote - employees work from their location of choice. ', 'Description of the job location', 'level-playing-field' ),
		];

		/**
		 * Filter the default attributes for a job listing.
		 *
		 * @since 1.0.0
		 *
		 * @param array $default_atts Array of shortcode attributes.
		 *
		 * @return array The shortcode attributes, maybe filtered.
		 */
		return apply_filters( 'lpf_single_job_listing_default_attributes', $default_atts );
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

		return [
			'job'      => $jobs_repository->find( $atts['id'] ),
			'partials' => [
				'job_details'      => static::JOB_DETAILS_PARTIAL,
				'job_apply_button' => static::JOB_APPLY_PARTIAL,
			],
		];
	}
}
