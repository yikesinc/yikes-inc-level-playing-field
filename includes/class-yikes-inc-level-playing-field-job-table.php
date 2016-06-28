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
	public function __construct() {
		// store the query args
		$this->query_args = $this->build_job_query_argument_array();
		$this->initialize_table();
	}

	// initialize the table, run the query etc.
	public function initialize_table() {
		// Run the query
		$query = new WP_Query( $this->query_args );
		if ( $query->have_postS() ) {
			?><ul><?php
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<li>' . get_the_title() . '</li>';
			}
			?></ul><?php
		}
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
