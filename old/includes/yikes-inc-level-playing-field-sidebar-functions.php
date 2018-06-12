<?php
/**
 * YIKES Inc Level Playing Field Sidebars
 *
 * Sidebar related functions and sidebar registration.
 *
 * @author 		YIKES, Inc.
 * @package 	yikes-inc-level-playing-field/Functions
 * @since     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include our custom widget
include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/widgets/widget.applicant-messenger-details.php' );

/**
 * Register Sidebars.
 *
 * @since 1.0.0
 */
function yikes_lpf_register_widgets() {
	register_sidebar( array(
		'name' => __( 'Applicant Messenger Sidebar', 'theme-slug' ),
		'id' => 'applicant-messenger',
		'description' => __( 'Displays on the applicant messenger page.', 'theme-slug' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'yikes_lpf_register_widgets', 999 );
