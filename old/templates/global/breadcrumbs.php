<?php
/**
 * Job breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/global/breadcrumbs.php.
 *
 * @since     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $breadcrumb ) ) {

	echo wp_kses_post( $wrap_before );

	foreach ( $breadcrumb as $key => $crumb ) {

		echo wp_kses_post( $before );

		if ( ! empty( $crumb[1] ) && count( $breadcrumb ) !== $key + 1 ) {
			echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
		} else {
			echo esc_html( $crumb[0] );
		}

		echo wp_kses_post( $after );

		if ( count( $breadcrumb ) !== $key + 1 ) {
			echo esc_attr( $delimiter );
		}
	}

	echo wp_kses_post( $wrap_after );

}
