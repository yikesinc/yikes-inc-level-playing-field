<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\ApplicantMeta;

$job       = $this->job;
$applicant = $this->applicant;
$post_type = $this->post_type;
?>

<div id="poststuff" class="single-applicant-view">
	<div class="metabox-holder columns-2" id="post-body">
		<div class="postbox-container" id="postbox-container-1">
			<div class="meta-box-sortables ui-sortable" id="side-sortables">
				<?php
					/**
					 * lpf_applicant_screen_sidebar hook.
					 *
					 * @hooked Applicant Status - 10
					 * @hooked Basic Info - 20
					 * @hooked Interview Details - 30
					 */
					do_action( 'lpf_applicant_screen_sidebar', $this );
				?>
			</div><!-- /meta-box-sortables -->
		</div><!-- /postbox-container-1 -->

		<div class="postbox-container" id="postbox-container-2">
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
				<?php
					/**
					 * lpf_applicant_screen_section_one hook.
					 *
					 * @hooked Applicant Info - 10
					 * @hooked Applicant Skills & Qualifications - 20
					 */
					do_action( 'lpf_applicant_screen_section_one', $this );
				?>
			</div><!-- /meta-box-sortables -->
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
				<?php
					/**
					 * lpf_applicant_screen_section_two hook.
					 *
					 * @hooked Applicant Messaging - 10
					 */
					do_action( 'lpf_applicant_screen_section_two', $this );
				?>
			</div><!-- /meta-box-sortables -->
		</div><!-- /postbox-container-2 -->
	</div><!-- /post-body metabox-holder columns-2 -->
	<br class="clear">
	</br>
</div><!-- /poststuff -->
