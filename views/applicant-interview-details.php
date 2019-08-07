<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

/** @var Applicant $applicant */
$applicant       = $this->applicant;
$display_details = $applicant->get_interview_details();
?>

<!-- Interview details sidebar -->
<div id="interview" class="postbox">
	<div class="inside">

		<!-- Interview Status (Always display a status). -->
		<p>
			<?php echo esc_html( $display_details['status'] ); ?>
		</p>
		
		<?php if ( array_key_exists( 'date', $display_details ) ) : ?>
		<!-- Interview Date. -->
		<p>
			<span class="label"><?php esc_html_e( 'Date:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $display_details['date'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'time', $display_details ) ) : ?>
		<!-- Interview Time. -->
		<p>
			<span class="label"><?php esc_html_e( 'Time:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $display_details['time'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'location', $display_details ) ) : ?>
		<!-- Interview Location. -->
		<p>
			<span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $display_details['location'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'message', $display_details ) ) : ?>
		<!-- Interview Message. -->
		<p>
			<span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span> <?php echo esc_html( $display_details['message'] ); ?>
		</p>
		<?php endif; ?>

	</div>
</div>
