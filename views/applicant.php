<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\View\View;

/**
 * Trigger the applicant screen first section.
 *
 * @hooked Applicant Info - 10
 * @hooked Applicant Skills & Qualifications - 20
 * @hooked Applicant Messaging - 30
 *
 * @param View $view The current view object.
 */
do_action( 'lpf_applicant_screen_metabox', $this );

/**
 * Trigger applicant post-processing.
 *
 * @hooked Update Viewed By - 10
 *
 * @param View $view The current view object.
 */
do_action( 'lpf_applicant_screen_rendered', $this );
