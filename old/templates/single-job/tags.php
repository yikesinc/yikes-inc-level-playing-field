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

$job_categories = wp_get_post_terms( get_the_ID(), 'job-tags' );

if ( $job_categories && ! empty( $job_categories ) ) {
	$length = count( $job_categories );
	echo wp_kses_post( '<div class="yikes-lpf-tags"><i class="lpf-job-icon lpf-job-icon-tags-icon" title="' . __( 'Job Tags', 'yikes-inc-level-playing-field' ) . '"></i>' );
	for ( $x = 0; $x <= $length; $x++ ) {
		echo wp_kses_post( '<a href="' . $job_categories[ $x ]->slug . '" title="' . $job_categories[ $x ]->name . '">' . $job_categories[ $x ]->name . '</a>' );
		if ( $x < ( count( $job_categories ) -  1 ) ) {
			echo ', ';
		}
	}
	echo wp_kses_post( '</div>' );
}
