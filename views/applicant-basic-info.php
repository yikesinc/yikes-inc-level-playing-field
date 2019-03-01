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

/** @var Applicant $applicant */
$applicant = $this->applicant;
?>
<div id="basic-info" class="postbox">
	<h2 class="hndle ui-sortable-handle">
		<span>
			<?php esc_html_e( 'Basic Applicant Information', 'yikes-level-playing-field' ); ?>
		</span>
	</h2>
	<div class="inside">
		<p class="location">
			<span class="label"><?php esc_html_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
			<?php
			foreach ( $applicant->get_address() as $field ) {
				echo esc_html( $field ), '<br>';
			}
			?>
		</p>
		<?php
		if ( ! empty( $applicant->get_cover_letter() ) ) :
			?>
		<p class="cover-letter">
			<span class="label"><?php esc_html_e( 'Cover Letter:', 'yikes-level-playing-field' ); ?></span>
			<a href="#"><?php esc_html_e( 'View Cover Letter', 'yikes-level-playing-field' ); ?></a>
		</p>
		<?php
		// @todo: Should HTML be allowed in the cover letter?
		?>
		<div class="cover-letter-content">
			<?php echo esc_html( $applicant->get_cover_letter() ); ?>
		</div>
			<?php
		endif;
			?>
	</div><!-- /inside -->
</div><!-- /postbox -->
