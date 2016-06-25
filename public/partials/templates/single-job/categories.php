<?php
/**
 * Job Sidebar
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/single-job/sidebar.php.
 *
 * @since     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$job_categories = wp_get_post_terms( get_the_ID(), 'job-categories' );

if ( $job_categories && ! empty( $job_categories ) ) {
	echo wp_kses_post( '<div class="yikes-lpf-categories"><i class="lpf-job-icon lpf-job-icon-categories-icon" title="' . __( 'Job Categories', 'yikes-inc-level-playing-field' ) . '"></i>' );
	foreach ( $job_categories as $job ) {
		echo wp_kses_post( '<a href="' . $job->slug . '" title="' . $job->name . '">' . $job->name . '</a>' );
	}
	echo wp_kses_post( '</div>' );
}
