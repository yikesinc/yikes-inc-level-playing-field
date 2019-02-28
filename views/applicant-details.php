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
$job       = $this->job;
?>
<!-- Avatar, nicknme and associated job -->
<div id="applicant-info" class="postbox">
	<div class="inside">
		<section id="header">
			<?php echo $applicant->get_avatar_img( 120 ); //phpcs:ignore WordPress.Security.EscapeOutput ?>
			<h5>
				<span class="label"><?php esc_html_e( 'Nickname:', 'yikes-level-playing-field' ); ?></span>
				<span id="editable-nick-name"><?php echo esc_html( $applicant->get_nickname() ); ?></span>
				<span id="edit-nickname-buttons">
					<button type="button" class="edit-nickname button button-small hide-if-no-js" aria-label="Edit nickname"><?php esc_html_e( 'Edit Nickname', 'yikes-level-playing-field' ); ?></button>
				</span>
			</h5>
			<h5>
				<span class="label"><?php esc_html_e( 'Job:', 'yikes-level-playing-field' ); ?></span>
				<?php echo esc_html( $job->get_title() ); ?>
			</h5>
		</section><!-- /header -->
	</div><!-- /inside -->							
	<br class="clear">
	</br>
</div><!-- /postbox -->