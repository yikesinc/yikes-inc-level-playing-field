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

if ( true === $this->is_cancel ) {
	$this->applicant->cancel_interview();
	?>
		<div><?php esc_html_e( 'Your interview request has been canceled', 'level-playing-field' ); ?></div>
	<?php
} elseif ( true === $this->is_confirm ) {
	$this->applicant->confirm_interview();
	?>
		<div><?php esc_html_e( 'Your interview request has been confirmed', 'level-playing-field' ); ?></div>
	<?php
}

$interview_status = $this->applicant->get_interview_status();

if ( ! $this->is_cancel && ( 'confirmed' === $interview_status || 'scheduled' === $interview_status ) ) {
	$interview = $this->applicant->get_interview();
	?>

	<div class="applicant-messaging-interview-confirm-container">
		<?php
		if ( ! $this->is_confirm ) {
			if ( 'confirmed' === $interview_status ) {
				?>
				<div class="applicant-messaging-interview-status"><?php esc_html_e( 'You have a confirmed interview.', 'level-playing-field' ); ?></div>
				<?php
			} else {
				?>
				<div class="applicant-messaging-interview-status"><?php esc_html_e( 'You have a pending interview request', 'level-playing-field' ); ?></div>
				<?php
			}
		}
		?>
			<div class="applicant-messaging-interview-confirm-details">
				<p>
					<span class="label"><?php esc_html_e( 'Date:', 'level-playing-field' ); ?></span>
					<?php echo esc_html( $interview['date'] ); ?>
				</p>
				<p>
					<span class="label"><?php esc_html_e( 'Time:', 'level-playing-field' ); ?></span>
					<?php echo esc_html( $interview['time'] ); ?>
				</p>
				<p>
					<span class="label"><?php esc_html_e( 'Location:', 'level-playing-field' ); ?></span>
					<?php echo esc_html( $interview['location'] ); ?>
				</p>
				<p>
					<span class="label"><?php esc_html_e( 'Message:', 'level-playing-field' ); ?></span>
					<?php echo esc_html( $interview['message'] ); ?>
				</p>
			</div>

		<?php
		if ( 'confirmed' !== $interview_status ) {
			?>
			<a href="<?php echo esc_url( $this->applicant->get_confirmation_endpoint() ); ?>" class="applicant-messaging-interview-confirm">
				&#x2714; <?php esc_html_e( 'Confirm Interview', 'level-playing-field' ); ?>
			</a>
			<a href="<?php echo esc_url( $this->applicant->get_cancellation_endpoint() ); ?>" class="applicant-messaging-interview-decline">
				&#10006; <?php esc_html_e( 'Decline Interview', 'level-playing-field' ); ?>
			</a>
			<?php
		}
		?>
	</div>
	<?php
}
