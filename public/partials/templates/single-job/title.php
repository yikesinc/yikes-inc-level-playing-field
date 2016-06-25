<?php
/**
 * Single Job title
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/single-job/title.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

the_title( '<h1 itemprop="name" class="job-title entry-title">', '</h1>' );
