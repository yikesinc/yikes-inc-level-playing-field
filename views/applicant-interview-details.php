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
			<?php // @todo: fetch interview data with $applicant->get_interview(). ?>
			<?php $interview = maybe_unserialize( $applicant->__get( 'interview' ) ); ?>
			<p><span class="label"><?php esc_html_e( 'Date:', 'yikes-level-playing-field' ); ?></span>
			<?php echo esc_html( $interview['date'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Time:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['time'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['location'] ); ?></p>
			<p><span class="label"><?php esc_html_e( 'Message:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $interview['message'] ); ?></p>
		<?php } else { ?>
			<p><span class="label">An interview has not been scheduled yet.</span>
		<?php } ?>
	</div>
</div>