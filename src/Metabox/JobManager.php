<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\PluginFactory;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Model\JobMeta;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;
use Yikes\LevelPlayingField\Model\JobMetaDropdowns;
use Yikes\LevelPlayingField\Blocks\JobListing;
use Yikes\LevelPlayingField\RequiredPages\ApplicationFormPage;

/**
 * Class JobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class JobManager extends AwesomeBaseMetabox implements AssetsAware {

	use AssetsAwareness;
	use JobMetaDropdowns;

	const CSS_HANDLE = 'lpf-admin-jobs-css';
	const CSS_URI    = 'assets/css/lpf-jobs-admin';
	const JS_HANDLE  = 'lpf-job-manager-js';
	const JS_URI     = 'assets/js/job-manager';

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
			$check     = get_metadata( 'post', $id, $key, $single );
			if ( is_string( $check ) ) {
				$check = json_decode( $check, true );
			}
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
		$job_types = $this->get_job_type_options();
		$job_types = array_map( function( $value, $name ) {
			return [
				'value' => $value,
				'name'  => $name,
			];
		}, array_keys( $job_types ), $job_types );
		$job_boxes = [
			'id'         => JobMeta::META_PREFIX . 'metabox',
			'title'      => __( 'Job Listing Information', 'yikes-level-playing-field' ),
			'pages'      => [ JobManagerCPT::SLUG ],
			'show_names' => true,
			'group'      => false,
			'context'    => $this->get_metabox_content(),
			'fields'     => [
				[
					'name'     => __( 'Job Status', 'yikes-level-playing-field' ),
					'desc'     => __( 'Is this job currently active or inactive', 'yikes-level-playing-field' ),
					'id'       => 'tax_input[' . JobStatus::SLUG . ']',
					'type'     => 'taxonomy-select',
					'taxonomy' => JobStatus::SLUG,
				],
				[
					'name'    => __( 'Job Type', 'yikes-level-playing-field' ),
					'desc'    => __( 'The type of job being offered', 'yikes-level-playing-field' ),
					'id'      => $this->prefix_field( JobMeta::TYPE ),
					'type'    => 'radio',
					'options' => $job_types,
				],
				[
					'name'    => __( 'Location', 'yikes-level-playing-field' ),
					'desc'    => __( 'Is this job at a location or can employees work remotely', 'yikes-level-playing-field' ),
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
					'name' => __( 'Location Address', 'yikes-level-playing-field' ),
					'desc' => __( 'Address where the job is located', 'yikes-level-playing-field' ),
					'id'   => $this->prefix_field( JobMeta::ADDRESS ),
					'type' => 'address',
				],
				[
					'name' => __( 'Application Success Message', 'yikes-level-playing-field' ),
					'desc' => __( "The message displayed after the job's application is submitted.", 'yikes-level-playing-field' ),
					'id'   => $this->prefix_field( JobMeta::APPLICATION_SUCCESS_MESSAGE ),
					'type' => 'textarea',
				],
				[
					'name'      => __( 'Application Form', 'yikes-level-playing-field' ),
					'desc'      => __( 'Choose the application form to use for this job', 'yikes-level-playing-field' ),
					'id'        => MetaLinks::APPLICATION,
					'type'      => 'select-post-type-select2',
					'post-type' => ApplicationManager::SLUG,
				],
				[
					'name'      => __( 'Application Form Page', 'yikes-level-playing-field' ),
					'desc'      => __( "Choose the page for this job's application form.", 'yikes-level-playing-field' ),
					'id'        => $this->prefix_field( JobMeta::APPLICATION_PAGE ),
					'type'      => 'select-post-type-select2',
					'post-type' => 'page',
					'std'       => ( new ApplicationFormPage() )->get_page_id( ApplicationFormPage::PAGE_SLUG ),
				],
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
	 * Get the context for the job manager metabox.
	 *
	 * If the new editor is enabled, show the metabox in the side. Otherwise show it in the normal table.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	private function get_metabox_content() {
		return PluginFactory::create()->is_new_editor_enabled() ? 'side' : 'normal';
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$script = new ScriptAsset( self::JS_HANDLE, self::JS_URI, [ 'wp-blocks' ] );
		$script->add_localization(
			'lpf_job_manager_data',
			[
				'disallowed_blocks' => [
					( new JobListing() )->get_block_slug(),
				],
			]
		);

		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
			$script,
		];
	}
}
