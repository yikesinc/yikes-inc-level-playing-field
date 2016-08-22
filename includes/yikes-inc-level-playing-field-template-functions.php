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

if ( ! function_exists( 'yikes_lpf_get_applicant_messenger_sidebar' ) ) {
	/**
	 * Get the Applicant Messenger Sidebar
	 */
	function yikes_lpf_get_applicant_messenger_sidebar() {
		lpf_get_template( 'global/applicant-messenger-sidebar.php' );
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

/**
 * Calculate the number of days since the job was posted to the site
 */
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
 * Function called when an application is rendered on the page
 * These scripts/styles are required for the popup to function properly
 * @return null Enqueue scripts and styles as needed
 * @since 1.0.0
 */
function yikes_lpf_load_application_assets() {
	wp_enqueue_style( 'lity.css', YIKES_LEVEL_PLAYING_FIELD_URL . 'public/css/min/lity.min.css' );
	wp_enqueue_script( 'lity.js', YIKES_LEVEL_PLAYING_FIELD_URL . 'public/js/min/lity.min.js', array( 'jquery', 'yikes-inc-level-playing-field' ), 'all', true );
}

/**
 * Append the shortcode to the end of the site content
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
if ( ! function_exists( 'append_job_listing_application' ) ) {
	function append_job_listing_application() {
		global $post;
		if ( ! isset( $post ) || 'jobs' !== $post->post_type ) {
			return;
		}
		yikes_lpf_load_application_assets();
		// Render the shortcode
		echo wp_kses_post( do_shortcode( '[lpf-application application="' . $post->ID . '"]' ) );
	}
	add_action( 'yikes_level_playing_field_after_single_job_summary', 'append_job_listing_application', 10 );
}
/**
 * End Global functions
 */
