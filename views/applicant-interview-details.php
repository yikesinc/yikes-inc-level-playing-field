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

<!-- Interview details sidebar -->
<div id="interview" class="postbox">
	<div class="inside">
		<?php if ( $applicant->get_interview_status() === 'scheduled' || $applicant->get_interview_status() === 'confirmed' ) { ?>
			<?php $interview = $applicant->get_interview(); ?>

			<?php if ( $applicant->get_interview_status() === 'scheduled' ) { ?>
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

			<?php if ( $applicant->get_interview_status() === 'confirmed' ) { ?>
				<p>
					<span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['location'] ); ?>
				</p>
				<p>
					<span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $interview['message'] ); ?>
				</p>
			<?php } ?>

		<?php } else { ?>
			<p><span class="label"><?php esc_html_e( 'An interview has not been scheduled yet', 'yikes-level-playing-field' ); ?>.</span>
		<?php } ?>
	</div>
</div>
