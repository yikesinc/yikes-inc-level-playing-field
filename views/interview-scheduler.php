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

$interview_status    = $this->applicant->get_interview_status();
$interview_closed    = 'scheduled' === $interview_status || 'confirmed' === $interview_status;
$interview_class     = $interview_closed ? 'disabled' : '';
$interview_help_text = ! $interview_closed ? '' : ( 'scheduled' === $interview_status ? __( 'An interview has already been scheduled.', 'yikes-level-playing-field' ) : __( 'An interview has already been confirmed.', 'yikes-level-playing-field' ) );
?>
<hr>

<div id="interview-scheduler-button-container">
	<button type="button" id="interview-scheduler" class="button button-primary <?php echo esc_attr( $interview_class ); ?>" title="<?php echo esc_attr( $interview_help_text ); ?>">
		<?php esc_html_e( 'Interview Scheduler', 'yikes-level-playing-field' ); ?>
		<span class="dashicons dashicons-arrow-down"></span>
	</button>
</div>

<div id="interview-scheduler-fields-container" class="hidden">

	<p><?php esc_html_e( 'These are the instructions explaining what it means to schedule an interview and how the un-anonymization process works.', 'yikes-level-playing-field' ); ?></p>

	<label for="interview-date" class="inline-label"><?php esc_html_e( 'Date', 'yikes-level-playing-field' ); ?>
		<input type="text" class="lpf-datepicker" id="interview-date" name="interview-date"/>
	</label>

	<label for="interview-time" class="inline-label"><?php esc_html_e( 'Time', 'yikes-level-playing-field' ); ?>
		<input type="text" class="lpf-timepicker" id="interview-time" name="interview-time"/>
	</label>

	<label for="interview-location" class="block-label"><?php esc_html_e( 'Location', 'yikes-level-playing-field' ); ?>
		<textarea type="text" id="interview-location" name="interview-location"></textarea>
	</label>

	<label for="interview-message" class="block-label"><?php esc_html_e( 'Message', 'yikes-level-playing-field' ); ?>
		<textarea type="text" id="interview-message" name="interview-message"></textarea>
	</label>
</div>
<div id="send-interview-request-button-container" class="hidden">
	<button type="button" id="send-interview-request" class="button button-primary">
		<?php esc_html_e( 'Send Interview Request', 'yikes-level-playing-field' ); ?>
	</button>
</div>
