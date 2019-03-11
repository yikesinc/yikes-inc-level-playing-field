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
$job       = $this->job;
$use_comma = (bool) apply_filters( 'lpf_single_job_template_address_use_comma', true, $job );
?>

<div class="job-page-job">

	<?php if ( $this->show_title ) : ?>
		<h4 class="job-page-job-title"><?php echo esc_html( $job->get_title() ); ?></h4>
	<?php endif; ?>

	<div class="job-page-job-meta">

		<?php if ( $this->show_description && ! empty( $job->get_content() ) ) : ?>
			<div class="job-page-job-description">
				<?php echo wp_kses_post( $job->get_content() ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $this->show_job_type && ! empty( $job->get_job_type() ) ) : ?>
			<div class="job-page-job-type">
				<?php echo esc_html( $this->job_type_text ); ?> <?php echo esc_html( $job->get_job_type() ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $this->show_location ) : ?>
			<div class="job-page-job-address">
				<span class="lpf-location"><?php echo esc_html( $this->location_text ); ?></span>
				<?php
				if ( $job->is_remote() ) {
					?>
					<span class="lpf-remote-location"><?php echo esc_html( $this->remote_location_text ); ?></span>
					<?php
				} elseif ( ! empty( $job->get_address() ) ) {
					$address   = $job->get_address();
					$use_comma = $use_comma && ! empty( $address['city'] ) && ( ! empty( $address['state'] ) || ! empty( $address['province'] ) );
					?>
					<address class="lpf-address">
						<div class="lpf-address1"><?php echo esc_html( $address['address-1'] ); ?></div>
						<div class="lpf-address2"><?php echo esc_html( $address['address-2'] ); ?></div>
						<span class="lpf-city"><?php echo esc_html( $address['city'] ); ?></span>
						<?php if ( ! empty( $address['province'] ) ) : ?>
							<span class="lpf-province"><?php echo esc_html( $address['province'] ); ?></span>
							<?php if ( $use_comma ) : ?>
								<span class="lpf-city-province-comma">,</span>
							<?php endif; ?>
						<?php elseif ( ! empty( $address['state'] ) ) : ?>
							<span class="lpf-city"><?php echo esc_html( $address['state'] ); ?></span>
							<?php if ( $use_comma ) : ?>
								<span class="lpf-city-state-comma">,</span>
							<?php endif; ?>
						<?php endif; ?>

						<div class="lpf-country"><?php echo esc_html( $address['country'] ); ?></div>
						<div class="lpf-zip"><?php echo esc_html( $address['zip'] ); ?></div>
					</address>
				<?php } ?>
			</div>
		<?php endif; ?>

	</div>
	<?php if ( $this->show_application_button ) : ?>
	<div class="job-page-application">
		<a href="<?php echo esc_url( $job->get_application_url() ); ?>"><button type="button" class="job-page-application-button"><?php echo esc_html( $this->button_text ); ?></button></a>
	</div>
	<?php endif; ?>
</div>
