<?php
/**
 * The Template for displaying all single job postings
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/single-job.php.
 *
 * @see 	    https://www.yikesplugins.com
 * @author 		YIKES, Inc.
 * @package 	yikes-inc-level-playing-field/templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Display the header
get_header( 'jobs' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'yikes_level_playing_field_before_main_content' );

while ( have_posts() ) : the_post();

	lpf_get_template_part( 'content', 'single-job' );

endwhile; // end of the loop.

/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 * @hooked yikes_lpf_categories - 10 (outputs categories assigned to the job listing)
 * @hooked yikes_lpf_tags - 11 (outputs tags assigned to the job listing)
 */
do_action( 'yikes_level_playing_field_after_main_content' );

/**
 * woocommerce_sidebar hook.
 *
 * @hooked yikes_lpf_get_sidebar - 10
 */
do_action( 'yikes_level_playing_field_sidebar' );

// Display the footer
get_footer( 'jobs' );
