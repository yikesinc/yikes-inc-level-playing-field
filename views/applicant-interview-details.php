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

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** @var Applicant $applicant */
$applicant       = $this->applicant;
$display_details = $applicant->get_interview_details();
?>

<!-- Interview details sidebar -->
<div id="interview" class="postbox">
	<div class="inside">

		<!-- Interview Status (Always display a status). -->
		<p>
			<?php echo esc_html( $display_details['status'] ); ?>
		</p>

		<?php if ( array_key_exists( 'date', $display_details ) ) : ?>
		<!-- Interview Date. -->
		<p>
			<span class="label"><?php echo esc_html( $display_details['date']['label'] ); ?></span> <?php echo esc_html( $display_details['date']['value'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'time', $display_details ) ) : ?>
		<!-- Interview Time. -->
		<p>
			<span class="label"><?php echo esc_html( $display_details['time']['label'] ); ?></span> <?php echo esc_html( $display_details['time']['value'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'location', $display_details ) ) : ?>
		<!-- Interview Location. -->
		<p>
			<span class="label"><?php echo esc_html( $display_details['location']['label'] ); ?></span> <?php echo esc_html( $display_details['location']['value'] ); ?>
		</p>
		<?php endif; ?>

		<?php if ( array_key_exists( 'message', $display_details ) ) : ?>
		<!-- Interview Message. -->
		<p>
			<span class="label"><?php echo esc_html( $display_details['message']['label'] ); ?></span> <?php echo esc_html( $display_details['message']['value'] ); ?>
		</p>
		<?php endif; ?>

	</div>
</div>
