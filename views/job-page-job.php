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

<div class="lpf-job-listing">
	<?php if ( $this->show_title ) : ?>
		<h3 class="lpf-job-listing-title"><?php echo esc_html( $job->get_title() ); ?></h3>
	<?php endif; ?>

	<?php if ( $this->show_description && ! empty( $job->get_content() ) ) : ?>
		<div class="lpf-job-listing-description-container">
			<h4 class="lpf-job-listing-description-header"><?php echo esc_html( $this->description_text ); ?></h4>
			<div class="lpf-job-listing-description">
				<?php echo wp_kses_post( apply_filters( 'lpf_the_content', $job->get_content() ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="lpf-job-listing-meta-container">

		<?php if ( $this->show_job_type && ! empty( $job->get_job_type() ) && $this->show_location ) : ?>
			<h4 class="lpf-job-listing-meta-header"><?php echo esc_html( $this->details_text ); ?></h4>
		<?php endif; ?>

		<div class="lpf-job-listing-meta">
			<?php if ( $this->show_job_type && ! empty( $job->get_job_type() ) ) : ?>
				<div class="lpf-job-listing-type">
					<span class="lpf-job-listing-meta-label lpf-job-listing-type-label"><?php echo esc_html( $this->job_type_text ); ?></span>
					<span class="lpf-job-listing-meta-content lpf-job-listing-type"><?php echo esc_html( $job->get_job_type() ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $this->show_location ) : ?>
				<div class="lpf-job-listing-location-container">
					<span class="lpf-job-listing-meta-label lpf-job-listing-location-label">
						<?php echo esc_html( $this->location_text ); ?>
					</span>
					<?php
					if ( $job->is_remote() ) {
						?>
						<span class="lpf-job-listing-location-remote">
							<?php echo esc_html( $this->remote_location_text ); ?>
						</span>
						<?php
					} elseif ( ! empty( $job->get_address() ) ) {
						$address   = $job->get_address();
						$use_comma = $use_comma && ! empty( $address['city'] ) && ( ! empty( $address['state'] ) || ! empty( $address['province'] ) );
						?>
						<address class="lpf-job-listing-location-address">
							<div class="lpf-address1"><?php echo esc_html( $address['address-1'] ); ?></div>
							<div class="lpf-address2"><?php echo esc_html( $address['address-2'] ); ?></div>
							<span class="lpf-city"><?php echo esc_html( $address['city'] ); ?></span>
							<?php if ( ! empty( $address['province'] ) ) : ?>
								<span class="lpf-province"><?php echo esc_html( $address['province'] ); ?></span>
								<?php if ( $use_comma ) : ?>
									<span class="lpf-city-province-comma">,</span>
								<?php endif; ?>
							<?php elseif ( ! empty( $address['state'] ) ) : ?>
								<span class="lpf-state"><?php echo esc_html( $address['state'] ); ?></span>
								<?php if ( $use_comma ) : ?>
									<span class="lpf-city-state-comma">,</span>
								<?php endif; ?>
							<?php endif; ?>
							<span class="lpf-zip"><?php echo esc_html( $address['zip'] ); ?></span>
							<div class="lpf-country"><?php echo esc_html( $address['country'] ); ?></div>
						</address>
					<?php } ?>
				</div>
			<?php endif; ?>
		</div>
	</div><!-- / .lpf-job-listing-meta-container -->
	<?php if ( $this->show_application_button && ! empty( $job->get_application() ) ) : ?>
	<div class="lpf-job-listing-button-container">
		<a href="<?php echo esc_url( $job->get_application_url() ); ?>">
			<button type="button" class="lpf-job-listing-button">
				<?php echo esc_html( $this->button_text ); ?>
			</button>
		</a>
	</div>
	<?php endif; ?>
</div>
