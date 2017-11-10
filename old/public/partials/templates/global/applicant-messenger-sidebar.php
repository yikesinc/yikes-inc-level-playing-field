<?php
/**
 * The sidebar that is displayed on the applicant messenger
 * Displys information about who you are communicating with, about what job, date applied etc.
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_active_sidebar( 'applicant-messenger' )  ) :
	get_sidebar( 'applicant-messenger' );
endif;
