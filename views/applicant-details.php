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
use Yikes\LevelPlayingField\Model\Job;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** @var Applicant $applicant */
$applicant = $this->applicant;

/** @var Job $job */
$job = $this->job;
?>
	<!-- Avatar, nicknme and associated job -->
	<div id="applicant-info">
		<section id="header">
			<?php echo $applicant->get_avatar_img( 120 ); ?>
			<h5>
				<span class="label"><?php esc_html_e( 'Nickname:', 'level-playing-field' ); ?></span>
				<span id="editable-nick-name"><?php echo esc_html( $applicant->get_nickname() ); ?></span>
				<span id="edit-nickname-buttons">
					<button type="button" class="edit-nickname button button-small hide-if-no-js" aria-label="Edit nickname"><?php esc_html_e( 'Edit Nickname', 'level-playing-field' ); ?></button>
				</span>
			</h5>
			<?php if ( ! empty( $applicant->get_name() ) ) : ?>
				<h5>
					<span class="label"><?php esc_html_e( 'Name:', 'level-playing-field' ); ?></span>
					<?php echo esc_html( $applicant->get_name() ); ?>
				</h5>
			<?php endif; ?>
			<h5>
				<span class="label"><?php esc_html_e( 'Job:', 'level-playing-field' ); ?></span>
				<?php echo esc_html( $job->get_title() ); ?>
			</h5>
		</section><!-- /header -->
		<br class="clear">
		</br>
	</div><!-- /applicant-info -->
