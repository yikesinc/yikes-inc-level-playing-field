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

use Yikes\LevelPlayingField\Comment\ApplicantMessage;

$comments = $this->comments;
?>

<div class="messaging-container">
	<?php
	if ( false === $this->is_metabox ) {
		echo $this->render_partial( $this->partials['interview_confirmation'] );
	}
	?>

	<div class="conversation-container">
	<?php

	if ( empty( $comments ) ) {
		?>
		<strong><?php esc_html_e( 'Start the conversation.', 'yikes-level-playing-field' ); ?></strong>
		<?php
	} else {

		$count    = count( $comments );
		$suppress = $count > 10;
		$counter  = 1;

		foreach ( $comments as $comment ) {

			if ( 1 === $counter && $suppress ) {
				?>
				<h3 id="conversation-show-all"><?php esc_html_e( 'Show All Messages', 'yikes-level-playing-field' ); ?></h3>
				<?php
			}

			$classes = [ 'lpf-message' ];

			$classes[] = $suppress && ( $count - $counter >= 10 ) ? 'hidden' : '';

			$classes[] = $comment->get_author() === ApplicantMessage::ADMIN_AUTHOR ? 'message-to-applicant' : 'message-from-applicant';

			$classes = array_map( 'sanitize_html_class', $classes );
			?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
				<span class="message"><?php echo nl2br( wp_kses( $comment->get_content(), [ 'br' => [] ] ) ); ?></span>
				<small class="message-timestamp"><?php echo esc_html( $comment->get_formatted_date() ); ?></small>
			</div>
			<?php
			$counter++;
		}
	}
	?>
	</div>

	<div class="new-applicant-message-container">
		<textarea id="new-applicant-message" name="new-applicant-message"></textarea>
	</div>

	<div class="send-new-applicant-message-container">
		<button type="button" id="send-new-applicant-message" class="button button-primary">Send</button>
	</div>
	<?php
	if ( true === $this->is_metabox ) {
		echo $this->render_partial( $this->partials['interview_scheduler'] );
	}
	?>
</div>
