<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * This is the template for the [lpf_all_jobs] shortcode.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Job;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * These are the available jobs.
 *
 * Storing as a custom variable here is not needed, but we hope it is less confusing for
 * those looking to extend this template.
 *
 * @var Job[] $jobs
 */
$jobs = $this->jobs;

/**
* Fires before displaying all of the Jobs for Level Playing Field.
*
* @param Job[] $jobs The array of Job objects.
*/
do_action( 'lpf_jobs_before', $jobs );

if ( $this->grouped_by_cat ) {
	echo $this->render_partial( $this->partials['jobs_by_category_list'] );
} else {
	echo $this->render_partial( $this->partials['jobs_list'] );
}
