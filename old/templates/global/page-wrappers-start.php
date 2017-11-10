<?php
/**
 * Content wrappers beginngs
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/global/page-wrappers-start.php.
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = get_option( 'template' );

switch ( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main" class="twentyeleven">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="twentytwelve">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc entry-content">';
		break;
	case 'twentyfifteen' :
		add_action( 'yikes_level_playing_field_before_single_job_summary', 'generate_twenty_fifteen_top_container' );
		add_action( 'yikes_level_playing_field_after_single_job_summary', 'generate_twenty_fifteen_bottom_container' );
		echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
		break;
	case 'twentysixteen' :
		echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
		break;
	default :
		echo '<div id="container"><div id="content" role="main">';
		break;
}

/**
 * Twenty Fifteen Helper
 */
function generate_twenty_fifteen_top_container() {
	echo '<div class="entry-content">';
}

/**
 * Twenty Fifteen Helper
 */
function generate_twenty_fifteen_bottom_container() {
	echo '<div class="entry-content">';
}
