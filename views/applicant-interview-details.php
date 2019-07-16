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
$applicant        = $this->applicant;
$interview_status = $applicant->get_interview_status();
?>

<!-- Interview details sidebar -->
<div id="interview" class="postbox">
	<div class="inside">
		<?php if ( 'scheduled' === $interview_status || 'confirmed' === $interview_status ) { ?>
			<?php $interview = $applicant->get_interview(); ?>

			<?php if ( 'scheduled' === $interview_status ) { ?>
				<p>
					<span class="label"><?php esc_html_e( 'Pending:', 'yikes-level-playing-field' ); ?></span> <?php esc_html_e( 'Awaiting Applicant Confirmation', 'yikes-level-playing-field' ); ?>
				</p>
			<?php } ?>

			<p>
				<span class="label"><?php esc_html_e( 'Date:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['date'] ); ?>
			</p>
			<p>
				<span class="label"><?php esc_html_e( 'Time:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['time'] ); ?>
			</p>

			<?php if ( 'confirmed' === $interview_status ) { ?>
				<p>
					<span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['location'] ); ?>
				</p>
				<p>
					<span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['message'] ); ?>
				</p>
			<?php } ?>

		<?php } ?>

		<?php if ( 'cancelled' === $interview_status ) { ?>
			<p>
				<?php esc_html_e( 'Interview request cancelled by the applicant.', 'yikes-level-playing-field' ); ?>
			</p>
		<?php } else { ?>
			<p>
				<?php esc_html_e( 'An interview has not been scheduled.', 'yikes-level-playing-field' ); ?>
			</p>
		<?php } ?>
	</div>
</div>
