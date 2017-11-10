<?php
/**
 * The template file for handling/displaying messeges between the applicant and admins
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/applicant-messenger.php.
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
get_header( 'applicant-messenger' );

/**
 * yikes_level_playing_field_before_application_messenger hook.
 *
 * @hooked yikes_lpf_output_content_wrapper_end - 5
 * @hooked generate_applicant_messenger_password_form - 10
 * @hooked generate_application_submission_response - 15
 * @hooked generate_message_submission_response - 15
 */
do_action( 'yikes_level_playing_field_before_applicant_messenger' );

/**
 * yikes_level_playing_field_application_messenger hook.
 *
 * @hooked render_applicant_messenger_header - 10
 * @hooked render_applicant_messenger_messages - 15
 * @hooked render_applicant_messenger_message_box - 20
 */
do_action( 'yikes_level_playing_field_applicant_messenger' );

/**
 * yikes_level_playing_field_after_application_messenger hook.
 */
do_action( 'yikes_level_playing_field_after_applicant_messenger' );

/**
 * yikes_level_playing_field_application_messenger_sidebar hook.
 *
 * @hooked yikes_lpf_output_content_wrapper_end - 5
 * @hooked get_applicant_messenger_sidebar - 10
 */
do_action( 'yikes_level_playing_field_applicant_messenger_sidebar' );

// Display the footer
get_footer( 'applicant-messenger' );
