<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Applicant;

/** @var Applicant $applicant */
$applicant = $this->applicant;
?>
<div id="interview" class="postbox">
	<h2 class="hndle ui-sortable-handle">
		<span>
			<?php esc_html_e( 'Interview Details', 'yikes-level-playing-field' ); ?>
		</span>
	</h2>
	<div class="inside">
		<?php if ( $applicant->get_interview_status() === 'scheduled' || $applicant->get_interview_status() === 'confirmed' ) { ?>
			<?php $interview = $applicant->get_interview(); ?>
			<p><span class="label"><?php esc_html_e( 'Date:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['date'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Time:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['time'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['location'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['message'] ); ?></p>
		<?php } else { ?>
			<p><span class="label"><?php esc_html_e( 'An interview has not been scheduled yet', 'yikes-level-playing-field' ); ?>.</span>
		<?php } ?>
	</div>
</div>