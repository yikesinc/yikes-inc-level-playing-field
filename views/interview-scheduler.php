<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$interview_status    = $this->applicant->get_interview_status();
$interview_closed    = 'scheduled' === $interview_status || 'confirmed' === $interview_status;
$interview_class     = $interview_closed ? 'disabled' : '';
$interview_help_text = ! $interview_closed ? '' : ( 'scheduled' === $interview_status ? __( 'An interview has already been scheduled.', 'level-playing-field' ) : __( 'An interview has already been confirmed.', 'level-playing-field' ) );
?>

<div id="interview-scheduler-container">
	<div id="interview-scheduler-button-container">
		<h4 class="interview-scheduler-title">
			<?php esc_html_e( 'Schedule an Interview', 'level-playing-field' ); ?>
		</h4>

		<button type="button" id="interview-scheduler" class="button button-primary <?php echo esc_attr( $interview_class ); ?>" title="<?php echo esc_attr( $interview_help_text ); ?>">
			<?php esc_html_e( 'Interview Request ', 'level-playing-field' ); ?>
			<span class="dashicons dashicons-arrow-down"></span>
		</button>
	</div>

	<div id="interview-scheduler-fields-container" class="hidden">
		<p class="interview-scheduler-instructions">
			<?php esc_html_e( 'Use the form below to send an interview request to the applicant.', 'level-playing-field' ); ?>
		</p>

		<label for="interview-date" class="inline-label"><?php esc_html_e( 'Date', 'level-playing-field' ); ?>
			<input type="text" class="lpf-datepicker" id="interview-date" name="interview-date"/>
		</label>

		<label for="interview-time" class="inline-label"><?php esc_html_e( 'Time', 'level-playing-field' ); ?>
			<input type="text" class="lpf-timepicker" id="interview-time" name="interview-time"/>
		</label>

		<label for="interview-location" class="block-label"><?php esc_html_e( 'Location details', 'level-playing-field' ); ?>
			<textarea type="text" id="interview-location" name="interview-location"></textarea>
		</label>

		<label for="interview-message" class="block-label"><?php esc_html_e( 'Message to Applicant', 'level-playing-field' ); ?>
			<textarea type="text" id="interview-message" name="interview-message"></textarea>
		</label>
	</div>
	<div id="send-interview-request-button-container" class="hidden">
		<button type="button" id="send-interview-request" class="button button-primary">
			<?php esc_html_e( 'Send Interview Request', 'level-playing-field' ); ?>
		</button>
	</div>
</div>
