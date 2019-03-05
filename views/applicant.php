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

?>
<div id="poststuff" class="single-applicant-view">
	<div class="metabox-holder columns-2" id="post-body">
		<div class="postbox-container" id="postbox-container-1">
			<div class="meta-box-sortables ui-sortable" id="side-sortables">
				<?php
					/**
					 * Trigger the applicant screen sidebar action.
					 *
					 * @hooked Applicant Status - 10
					 * @hooked Basic Info - 20
					 * @hooked Interview Details - 30
					 *
					 * @param View $view The current view object.
					 */
					do_action( 'lpf_applicant_screen_sidebar', $this );
				?>
			</div><!-- /meta-box-sortables -->
		</div><!-- /postbox-container-1 -->
		<div class="postbox-container" id="postbox-container-2">
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
				<?php
					/**
					 * Trigger the applicant screen first section.
					 *
					 * @hooked Applicant Info - 10
					 * @hooked Applicant Skills & Qualifications - 20
					 *
					 * @param View $view The current view object.
					 */
					do_action( 'lpf_applicant_screen_section_one', $this );
				?>
			</div><!-- /meta-box-sortables -->
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
				<?php
					/**
					 * Trigger the applicant screen second section.
					 *
					 * @hooked Applicant Messaging - 10
					 *
					 * @param View $view The current view object.
					 */
					do_action( 'lpf_applicant_screen_section_two', $this );
				?>
			</div><!-- /meta-box-sortables -->
		</div><!-- /postbox-container-2 -->
	</div><!-- /post-body metabox-holder columns-2 -->
	<br class="clear">
</div><!-- /poststuff -->
