<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Job;

/** @var Job $job */
$job        = $this->job;
$show_title = (bool) apply_filters( 'lpf_single_job_template_show_title', true, $job );
$use_comma  = (bool) apply_filters( 'lpf_single_job_template_address_use_comma', true, $job );
?>

<div class="job-page-job">

	<?php if ( $show_title ) : ?>
		<h4 class="job-page-job-title"><?php echo esc_html( $job->get_title() ); ?></h4>
	<?php endif; ?>

	<div class="job-page-job-meta">

		<?php if ( ! empty( $job->get_description() ) ) : ?>
			<div class="job-page-job-description">
				<?php echo wp_kses_post( wpautop( $job->get_description() ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $job->get_type() ) ) : ?>
			<div class="job-page-job-type">
				<?php esc_html_e( 'Type:', 'yikes-level-playing-field' ); ?> <?php echo esc_html( $job->get_type() ); ?>
			</div>
		<?php endif; ?>

		<div class="job-page-job-address">
			<span class="lpf-location"><?php esc_attr_e( 'Location:', 'yikes-level-playing-field' ); ?></span>
			<?php
			if ( $job->is_remote() ) {
				echo esc_html_x( 'Remote', 'Description of the job location', 'yikes-level-playing-field' );
			} elseif ( ! empty( $job->get_address() ) ) {
				$address   = $job->get_address();
				$use_comma = $use_comma && ! empty( $address['city'] ) && ( ! empty( $address['state'] ) || ! empty( $address['province'] ) );
				?>
				<address id="lpf-address">
					<div id="lpf-address1"><?php echo esc_html( $address['address-1'] ); ?></div>
					<div id="lpf-address2"><?php echo esc_html( $address['address-2'] ); ?></div>
					<span id="lpf-city"><?php echo esc_html( $address['city'] ); ?></span>
					<?php if ( ! empty( $address['province'] ) ) : ?>
						<span id="lpf-province"><?php echo esc_html( $address['province'] ); ?></span>
						<?php if ( $use_comma ) : ?>
							<span id="lpf-city-province-comma">,</span>
						<?php endif; ?>
					<?php elseif ( ! empty( $address['state'] ) ) : ?>
						<span id="lpf-city"><?php echo esc_html( $address['state'] ); ?></span>
						<?php if ( $use_comma ) : ?>
							<span id="lpf-city-state-comma">,</span>
						<?php endif; ?>
					<?php endif; ?>

					<div id="lpf-country"><?php echo esc_html( $address['country'] ); ?></div>
					<div id="lpf-zip"><?php echo esc_html( $address['zip'] ); ?></div>
				</address>
			<?php } ?>
		</div>

	</div>
	<!-- todo: Output application form -->
</div>
