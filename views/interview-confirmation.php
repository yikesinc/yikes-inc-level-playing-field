<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

if ( true === $this->is_cancel ) {
	$this->applicant->cancel_interview();
	?>
		<div><?php esc_html_e( 'Your interview request has been canceled', 'yikes-level-playing-field' ); ?></div>
	<?php
} elseif ( true === $this->is_confirm ) {
	$this->applicant->confirm_interview();
	?>
		<div><?php esc_html_e( 'Your interview request has been confirmed', 'yikes-level-playing-field' ); ?></div>
	<?php
}

$interview_status = $this->applicant->get_interview_status();

if ( ! $this->is_cancel && ( 'confirmed' === $interview_status || 'scheduled' === $interview_status ) ) {
	$interview = maybe_unserialize( $this->applicant->__get( 'interview' ) );

	if ( ! $this->is_confirm ) {
		if ( 'confirmed' === $interview_status ) {
			?>
			<div><?php esc_html_e( 'You have a confirmed interview.', 'yikes-level-playing-field' ); ?></div>
			<?php
		} else {
			?>
			<div><?php esc_html_e( 'You have a pending interview request', 'yikes-level-playing-field' ); ?></div>
			<?php
		}
	}
	?>
		<p>
			<span class="label"><?php esc_html_e( 'Date:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['date'] ); ?>
		</p>
		<p>
			<span class="label"><?php esc_html_e( 'Time:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['time'] ); ?>
		</p>
		<p>
			<span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['location'] ); ?>
		</p>
		<p>
			<span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['message'] ); ?>
		</p>

	<?php
	if ( 'confirmed' !== $interview_status ) {
		?>
		<a href="<?php echo esc_url( $this->applicant->get_confirmation_endpoint() ); ?>"><?php esc_html_e( 'Confirm Interview', 'yikes-level-playing-field' ); ?></a> |
		<a href="<?php echo esc_url( $this->applicant->get_cancellation_endpoint() ); ?>"><?php esc_html_e( 'Decline Interview', 'yikes-level-playing-field' ); ?></a>
		<?php
	}
}
