<?php
/**
 * Level Playing Field Template Functions
 *
 * Functions for the templating system.
 *
 * @author   YIKES, Inc.
 * @category Core
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Begin Global Functions
 */
if ( ! function_exists( 'yikes_lpf_output_content_wrapper' ) ) {
	/**
	 * Output the start of the page wrapper.
	 *
	 */
	function yikes_lpf_output_content_wrapper() {
		lpf_get_template( 'global/page-wrappers-start.php' );
	}
}

if ( ! function_exists( 'yikes_lpf_output_content_wrapper_end' ) ) {
	/**
	 * Output the end of the page wrapper.
	 *
	 */
	function yikes_lpf_output_content_wrapper_end() {
		lpf_get_template( 'global/page-wrappers-end.php' );
	}
}

if ( ! function_exists( 'yikes_lpf_get_sidebar' ) ) {
	/**
	 * Get the job sidebar template.
	 */
	function yikes_lpf_get_sidebar() {
		lpf_get_template( 'single-job/sidebar.php' );
	}
}

if ( ! function_exists( 'yikes_lpf_breadcrumbs' ) ) {
	/**
	 * Output the YIKES, Inc. Level Playing Field Job Breadcrumbs.
	 *
	 * @param array $args
	 */
	function yikes_lpf_breadcrumbs( $args = array() ) {
		$args = wp_parse_args( $args, apply_filters( 'yikes_level_playing_field_breadcrumb_defaults', array(
			'delimiter'   => '&nbsp;&#x203A;&nbsp;',
			'wrap_before' => '<nav class="yikes-lpf-breadcrumbs" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'yikes-inc-level-playing-field' ),
		) ) );
		$breadcrumbs = new YIKES_Level_Playing_Field_Breadcrumbs();
		if ( $args['home'] ) {
			$breadcrumbs->add_crumb( $args['home'], apply_filters( 'yikes_level_playing_field_breadcrumb_home_url', home_url() ) );
		}
		$args['breadcrumb'] = $breadcrumbs->generate();
		lpf_get_template( 'global/breadcrumbs.php', $args );
	}
}

if ( ! function_exists( 'yikes_lpf_categories' ) ) {
	/**
	 * Display the job categories
	 */
	function yikes_lpf_categories() {
		lpf_get_template( 'single-job/categories.php' );
	}
}

if ( ! function_exists( 'yikes_lpf_tags' ) ) {
	/**
	 * Display the job tags
	 */
	function yikes_lpf_tags() {
		lpf_get_template( 'single-job/tags.php' );
	}
}

if ( ! function_exists( 'yikes_lpf_posted_on' ) ) {
	/**
	 * Display the date this job was posted on
	 */
	function yikes_lpf_posted_on() {
		lpf_get_template( 'single-job/posted-on.php' );
	}
}

if ( ! function_exists( 'lpf_calculate_days_since_posting' ) ) {
	function lpf_calculate_days_since_posting( $date ) {
		$date_diff = strtotime( 'now' ) - $date;
		$days_since_posting = absint( floor( $date_diff / ( 60 * 60 * 24 ) ) );
		if ( 0 < $days_since_posting ) {
			return sprintf( _n( 'Job Posted %s day ago.', 'Job Posted %s days ago.', $days_since_posting, 'yikes-inc-level-playing-field' ), $days_since_posting );
		}
	}
}

/**
 * End Global functions
 */
