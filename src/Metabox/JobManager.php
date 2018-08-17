<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Model\JobMeta;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class JobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobManager extends AwesomeBaseMetabox implements AssetsAware {

	use AssetsAwareness;

	const JS_HANDLE = 'lpf-job-manager-js';
	const JS_URI    = 'assets/js/job-manager';

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		$this->register_assets();

		// Enqueue the meta box assets.
		add_action( 'add_meta_boxes_' . JobManagerCPT::SLUG, function() {
			$this->enqueue_assets();
		} );

		// Filter post meta to save JSON.
		add_filter( 'update_post_metadata', function( $check, $id, $key, $value, $prev_value ) {
			if ( ! isset( JobMeta::JSON_PROPERTIES[ $key ] ) ) {
				return $check;
			}

			// Make sure to prevent infinite recursion in the filter.
			static $filtering = false;
			if ( $filtering ) {
				return $check;
			}

			// Attempt to json_encode() the new data.
			$json = json_encode( $value );

			// Bail early if json_encode() fails, or if the old value is the same as the new value.
			if ( false === $json || $prev_value === $json ) {
				return $json;
			}

			$filtering = true;
			$check     = update_metadata( 'post', $id, $key, $json, $prev_value );
			$filtering = false;

			return $check;
		}, 10, 5 );

		// Filter post meta to retrieve JSON.
		add_filter( 'get_post_metadata', function( $check, $id, $key, $single ) {
			if ( ! isset( JobMeta::JSON_PROPERTIES[ $key ] ) ) {
				return $check;
			}

			// Make sure to prevent infinite recursion in the filter.
			static $filtering = false;
			if ( $filtering ) {
				return $check;
			}

			$filtering = true;
			$check     = json_decode( get_metadata( 'post', $id, $key, $single ), true );
			$filtering = false;

			return $check;
		}, 10, 4 );
	}

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
			'id'         => JobMeta::META_PREFIX . 'metabox',
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
							'id'   => $this->prefix_field( JobMeta::DESCRIPTION ),
							'type' => 'wysiwyg',
						],
						[
							'name'    => __( 'Job Type', 'yikes-level-playing-field' ),
							'desc'    => __( 'The type of job being offered', 'yikes-level-playing-field' ),
							'id'      => $this->prefix_field( JobMeta::TYPE ),
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
							'id'      => $this->prefix_field( JobMeta::LOCATION ),
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
							'id'   => $this->prefix_field( JobMeta::ADDRESS ),
							'type' => 'address',
						],
						[
							'name'      => __( 'Application', 'yikes-level-playing-field' ),
							'desc'      => __( 'The application to use for this job', 'yikes-level-playing-field' ),
							'id'        => MetaLinks::APPLICATION,
							'type'      => 'select-post-type',
							'post-type' => ApplicationManager::SLUG,
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

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new ScriptAsset( self::JS_HANDLE, self::JS_URI ),
		];
	}
}
