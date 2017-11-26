<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Model\JobMeta;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class JobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobManager extends AwesomeBaseMetabox {

	/**
	 * Get the prefix for use with meta fields.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_prefix() {
		return JobMeta::META_PREFIX;
	}

	/**
	 * Register meta boxes.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 *
	 * @param array $meta_boxes Array of existing meta boxes.
	 *
	 * @return array The filtered meta boxes.
	 */
	public function register_boxes( $meta_boxes ) {
		$job_boxes = [
			'id'         => $this->prefix_field( 'metabox' ),
			'title'      => __( 'General Info', 'yikes-level-playing-field' ),
			'pages'      => [ JobManagerCPT::SLUG ],
			'show_names' => true,
			'group'      => true,
			'fields'     => [
				[
					'name'   => __( 'General Info', 'yikes-level-playing-field' ),
					'id'     => 'general',
					'type'   => 'group',
					'fields' => [
						[
							'name'     => __( 'Status', 'yikes-level-playing-field' ),
							'desc'     => __( 'The job status', 'yikes-level-playing-field' ),
							'id'       => 'tax_input[' . JobStatus::SLUG . ']',
							'type'     => 'taxonomy-select',
							'taxonomy' => JobStatus::SLUG,
						],
						[
							'name' => __( 'Job Description', 'yikes-level-playing-field' ),
							'desc' => __( 'General overview of the Job and its requirements.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( 'description' ),
							'type' => 'wysiwyg',
						],
						[
							'name'    => __( 'Job Type', 'yikes-level-playing-field' ),
							'desc'    => __( 'The type of job being offered', 'yikes-level-playing-field' ),
							'id'      => $this->prefix_field( 'type' ),
							'type'    => 'radio-inline',
							'options' => [
								[
									'name'  => __( 'Full Time', 'yikes-level-playing-field' ),
									'value' => 'full-time',
								],
								[
									'name'  => __( 'Part Time', 'yikes-level-playing-field' ),
									'value' => 'part-time',
								],
								[
									'name'  => __( 'Contract', 'yikes-level-playing-field' ),
									'value' => 'contract',
								],
								[
									'name'  => __( 'Per Diem', 'yikes-level-playing-field' ),
									'value' => 'per-diem',
								],
								[
									'name'  => __( 'Other', 'yikes-level-playing-field' ),
									'value' => 'other',
								],
							],
						],
						[
							'name'    => __( 'Location', 'yikes-level-playing-field' ),
							'id'      => $this->prefix_field( 'location' ),
							'type'    => 'radio-inline',
							'options' => [
								[
									'name'  => __( 'Address', 'yikes-level-playing-field' ),
									'value' => 'address',
								],
								[
									'name'  => __( 'Remote', 'yikes-level-playing-field' ),
									'value' => 'remote',
								],
							],
						],
						[
							'name' => __( 'Address', 'yikes-level-playing-field' ),
							'desc' => __( 'Address where the job is located', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( 'address' ),
							'type' => 'address',
						],
					],
				],
				// Applicants here.
			],
		];

		/**
		 * Filter the Job Manager metabox fields.
		 *
		 * @param array $job_boxes The Job metbox array.
		 */
		$job_boxes    = apply_filters( 'lpf_job_metabox_fields', $job_boxes );
		$meta_boxes[] = $job_boxes;

		return $meta_boxes;
	}
}
