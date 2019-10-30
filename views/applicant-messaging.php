<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Comment\ApplicantMessage;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$comments = $this->comments;
?>
<!-- Applicant Messaging -->
<div id="applicant-messaging">
	<?php
	if ( false === $this->is_metabox ) {
		?>
		<h2>Your Messages</h2>
	<?php } else { ?>
		<h2 class="lpf_mbox_title"><?php esc_html_e( 'Applicant Messaging', 'level-playing-field' ); ?></h2>
	<?php } ?>

	<div class="messaging-container">
		<?php
		if ( false === $this->is_metabox ) {
			echo $this->render_partial( $this->partials['interview_confirmation'] );
		}
		?>

		<div class="conversation-container <?php echo count( $comments ) > 0 ? 'has-conversation' : ''; ?>">
		<?php

		if ( empty( $comments ) ) {
			?>
			<p class="conversation-container-text"><?php esc_html_e( 'Start a conversation with this applicant.', 'level-playing-field' ); ?></p>
			<?php
		} else {

			foreach ( $comments as $comment ) {

				$classes   = [ 'lpf-message' ];
				$classes[] = $this->is_metabox ? 'message-metabox' : 'message-page-template';
				$classes[] = $comment->get_author() === ApplicantMessage::ADMIN_AUTHOR ? 'message-to-applicant' : 'message-from-applicant';
				$classes   = array_map( 'sanitize_html_class', $classes );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<div class="message">
						<?php
						echo nl2br( wp_kses( $comment->get_content(), [
							'br'   => [],
							'div'  => [ 'class' => [] ],
							'span' => [ 'class' => [] ],
						] ) );
						?>
					</div>
					<div class="message-timestamp"><?php echo esc_html( $comment->get_formatted_date() ); ?></div>
				</div>
				<?php
			}
		}
		?>
		</div>

		<div class="new-applicant-message-container">
			<h4 class="applicant-message-title">
				<?php esc_html_e( 'Send a New Message', 'level-playing-field' ); ?>
			</h4>
			<textarea id="new-applicant-message" name="new-applicant-message"></textarea>
		</div>

		<div class="send-new-applicant-message-container">
			<button type="button" id="send-new-applicant-message" class="button button-primary">Send Your Message</button>
		</div>
		<?php
		if ( true === $this->is_metabox ) {
			echo $this->render_partial( $this->partials['interview_scheduler'] );
		}
		?>
	</div>
</div><!-- /applicant-messaging -->
