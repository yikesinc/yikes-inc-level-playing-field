<?php
/**
 * Level Playing Field Template hooks
 *
 * Action Hooks & Filters for the templating system.
 *
 * @author   YIKES, Inc.
 * @category Core
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Content Wrappers.
 *
 * @see yikes_lpf_output_content_wrapper()
 * @see yikes_lpf_output_content_wrapper_end()
 */
add_action( 'yikes_level_playing_field_before_main_content', 'yikes_lpf_output_content_wrapper', 10 );
add_action( 'yikes_level_playing_field_after_main_content', 'yikes_lpf_output_content_wrapper_end', 10 );

/**
 * Sidebar.
 *
 * @see yikes_lpf_get_sidebar()
 */
add_action( 'yikes_level_playing_field_sidebar', 'yikes_lpf_get_sidebar', 10 );

/**
 * Breadcrumbs.
 *
 * @see yikes_lpf_breadcrumbs()
 */
add_action( 'yikes_level_playing_field_before_main_content', 'yikes_lpf_breadcrumbs', 20, 0 );

/**
 * Categories.
 *
 * @see yikes_lpf_categories()
 */
add_action( 'yikes_level_playing_field_single_job_summary', 'yikes_lpf_categories', 10, 0 );

/**
 * Tags.
 *
 * @see yikes_lpf_tags()
 */
add_action( 'yikes_level_playing_field_single_job_summary', 'yikes_lpf_tags', 11, 0 );

/**
 * Posted On Details.
 *
 * @see yikes_lpf_posted_on()
 */
add_action( 'yikes_level_playing_field_single_job_summary', 'yikes_lpf_posted_on', 12, 0 );
