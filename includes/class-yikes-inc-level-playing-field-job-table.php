<?php

/**
 * Handles the display of our job posting table on the front end
 * whent he user uses the shortcode [lpf-jobs]
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */

/**
 * Render the job listing table (chronologically based on WHEN they were posted)
 * *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Job_Table {

	private $query_args;

	// constructor
	public function __construct( $shortcode_attributes ) {
		// store the query args
		$this->query_args = $this->build_job_query_argument_array();
		if ( 'table' === $shortcode_attributes['type'] ) {
			$this->initialize_table();
		} else {
			$this->initialize_list();
		}
	}

	// initialize the table, run the query etc.
	public function initialize_table() {
		// Run the query
		$query = new WP_Query( $this->query_args );
		if ( $query->have_posts() ) {
			// Get and store table headers
			$table_headers = $this->get_job_list_table_headers();
			// Load the Job Listing table template locally from our theme
			if ( file_exists( get_stylesheet_directory() . '/yikes-inc-level-playing-field/job-listing-table-template.php' ) ) {
				require_once( get_stylesheet_directory() . '/yikes-inc-level-playing-field/job-listing-table-template.php' );
			} else {
				// Load it from the plugin source
				require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'public/partials/templates/job-listing-table-template.php' );
			}
		}
	}

	// initialize the job list, run the query etc.
	public function initialize_list() {
		// Run the query
		$query = new WP_Query( $this->query_args );
		if ( $query->have_posts() ) {
			// Get and store table headers
			$table_headers = $this->get_job_list_table_headers();
			// Load the Job Listing table template locally from our theme
			if ( file_exists( get_stylesheet_directory() . '/yikes-inc-level-playing-field/job-listing-list-template.php' ) ) {
				require_once( get_stylesheet_directory() . '/yikes-inc-level-playing-field/job-listing-list-template.php' );
			} else {
				// Load it from the plugin source
				require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'public/partials/templates/job-listing-list-template.php' );
			}
		}
	}

	/**
	 * Build an array of table headers
	 * Note: Passed through yikes_level_playing_field_job_table_headers filter
	 * @return array Array of table headers to be used.
	 * @since 1.0.0
	 */
	public function get_job_list_table_headers() {
		// Setup and return the table headers
		return apply_filters( 'yikes_level_playing_field_job_table_headers', array(
			__( 'Job Title', 'yikes-inc-level-playing-field' ) => array(
				'type' => 'text',
				'break_point' => 's',
				'meta_key' => 'title',
			),
			__( 'Position', 'yikes-inc-level-playing-field' ) => array(
				'type' => 'text',
				'break_point' => 's',
				'meta_key' => 'position',
			),
			__( 'Location', 'yikes-inc-level-playing-field' ) => array(
				'type' => 'text',
				'break_point' => 's',
				'meta_key' => 'location',
			),
			__( 'Salary', 'yikes-inc-level-playing-field' ) => array(
				'type' => 'text',
				'break_point' => 's',
				'meta_key' => '_compensation_details',
			),
		) );
	}

	/**
	 * Build the jobs query arguments array
	 * @return array Complete array of query args.
	 * @since 1.0.0
	 */
	public function build_job_query_argument_array() {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : (int) 1;
		// return the query args
		return apply_filters( 'yikes_level_playing_field_job_query_args', array(
			'post_type' => 'jobs',
			'posts_per_page' => 50,
			'paged' => $paged,
		) );
	}
}
