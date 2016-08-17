<?php
/**
 * The template file for handling/displaying messeges between the applicant and admins
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/application-messenger.php.
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
get_header( 'application-messenger' );

/**
 * yikes_level_playing_field_before_application_messenger hook.
 */
do_action( 'yikes_level_playing_field_before_application_messenger' );

/**
 * yikes_level_playing_field_application_messenger hook.
 *
 * @hooked render_messenger_application_header - 10
 * @hooked render_messenger_application_message_box - 20
 */
do_action( 'yikes_level_playing_field_application_messenger' );

/**
 * yikes_level_playing_field_after_application_messenger hook.
 */
do_action( 'yikes_level_playing_field_after_application_messenger' );

/**
 * yikes_level_playing_field_application_messenger_sidebar hook.
 */
do_action( 'yikes_level_playing_field_application_messenger_sidebar' );

// Display the footer
get_footer( 'application-messenger' );
