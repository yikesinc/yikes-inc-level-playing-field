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

/**
* Fires before displaying all of the Jobs for Level Playing Field.
*
* @param Job[] $jobs The array of Job objects.
*/
do_action( 'lpf_jobs_before', $jobs );

if ( $this->grouped_by_cat ) {
	echo $this->render_partial( $this->partials['jobs_by_category_list'] ); // phpcs:ignore WordPress.Security.EscapeOutput
} else {
	echo $this->render_partial( $this->partials['jobs_list'] ); // phpcs:ignore WordPress.Security.EscapeOutput
}


/**
 * Fires after displaying all of the Jobs.
 *
 * @param Job[] $jobs The array of Job objects.
 */
do_action( 'lpf_jobs_after', $jobs );
