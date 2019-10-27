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

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Trigger the applicant screen first section.
 *
 * @hooked Applicant Info - 10
 * @hooked Applicant Skills & Qualifications - 20
 * @hooked Applicant Messaging - 30
 *
 * @param View  $view    The current view object.
 * @param array $context The context for the current view.
 */
do_action( 'lpf_applicant_screen_metabox', $this, $this->_context_ );

/**
 * Trigger applicant post-processing.
 *
 * @hooked Update Viewed By - 10
 *
 * @param View  $view    The current view object.
 * @param array $context The context for the current view.
 */
do_action( 'lpf_applicant_screen_rendered', $this, $this->_context_ );
