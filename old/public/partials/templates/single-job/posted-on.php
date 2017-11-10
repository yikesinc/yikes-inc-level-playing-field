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

$posted_on_date = strtotime( get_the_date( 'm/d/Y' ) );
$today = strtotime( date( 'm/d/Y', strtotime( 'now' ) ) );
$icon = '<i class="lpf-job-icon lpf-job-icon-clock-icon" title="' . sprintf( esc_attr__( 'Job Originally Posted On %s.', 'yikes-inc-level-playing-field' ), get_the_date() ) . '"></i>';

echo wp_kses_post( '<div class="yikes-lpf-posted-on">' );

if ( $today === $posted_on_date ) {
	printf( esc_attr__( '%s Posted Today.', 'yikes-inc-level-playing-field' ), wp_kses_post( $icon ) );
} else {
	printf( esc_attr( '%s %s' ), wp_kses_post( $icon ), esc_attr( lpf_calculate_days_since_posting( strtotime( get_the_date() ) ) ) );
}


echo wp_kses_post( '</div>' );
