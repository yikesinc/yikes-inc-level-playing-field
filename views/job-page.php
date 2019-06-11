<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * This is the template for the [lpf_all_jobs] shortcode.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Job;
use Yikes\LevelPlayingField\Shortcode\Job as JobShortcode;

/**
 * These are the available jobs.
 *
 * Storing as a custom variable here is not needed, but we hope it is less confusing for
 * those looking to extend this template.
 *
 * @var Job[] $jobs
 */
$jobs      = $this->jobs;
$use_comma = (bool) apply_filters( 'lpf_single_job_template_address_use_comma', true, $jobs );

/**
 * Filter the jobs displayed in the job listings shortcode.
 *
 * @since %VERSION%
 *
 * @param array $jobs Array of Job objects.
 *
 * @return array Array of Job objects, maybe filtered.
 */
$jobs = apply_filters( 'lpf_job_listings_jobs', $this->jobs );
?>

<ul class="lpf-jobs-list">
	<?php
	/**
	 * Fires before displaying all of the Jobs for Level Playing Field.
	 *
	 * @param Job[] $jobs The array of Job objects.
	 */
	do_action( 'lpf_jobs_before', $jobs );

	foreach ( $jobs as $job ) {
		?>

		<li class="lpf-jobs-list-item">
			<h4 class="lpf-jobs-list-job-title">
				<a href="<?php echo esc_url( get_permalink( $job->get_id() ) ); ?>">
					<?php echo esc_html( $job->get_title() ); ?>
				</a>
			</h4>
		<?php

		if ( $this->show_desc && ( 'true' === $this->show_desc || '1' === $this->show_desc ) && ( ! empty( $job->get_content() ) ) ) :
			?>
			<div class="lpf-jobs-list-description">
			<?php
			echo 'full' === $this->desc_type ? apply_filters( 'lpf_the_content', $job->get_content() ) : $job->get_excerpt();
			?>
			</div>
			<?php
		endif;
		if ( $this->show_details && ( 'true' === $this->show_details || '1' === $this->show_details ) ) :
			?>
			<div class="lpf-job-listing-meta-container">
				<h4 class="lpf-job-listing-meta-header"><?php echo esc_html( $this->details_text ); ?></h4>
				<div class="lpf-job-listing-meta">
				<?php if ( ! empty( $job->get_job_type() ) ) : ?>
					<div class="lpf-job-listing-type">
						<span class="lpf-job-listing-meta-label lpf-job-listing-type-label"><?php echo esc_html( $this->job_type_text ); ?></span>
						<span class="lpf-job-listing-meta-content lpf-job-listing-type"><?php echo esc_html( $job->get_job_type() ); ?></span>
					</div>
				<?php endif; ?>
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
								<?php if ( $use_comma && ! empty( $address['zip'] ) ) : ?>
									<span class="lpf-city-province-comma">,</span>
								<?php endif; ?>
							<?php elseif ( ! empty( $address['state'] ) ) : ?>
								<span class="lpf-state"><?php echo esc_html( $address['state'] ); ?></span>
								<?php if ( $use_comma && ! empty( $address['zip'] ) ) : ?>
									<span class="lpf-city-state-comma">,</span>
								<?php endif; ?>
							<?php endif; ?>
							<span class="lpf-zip"><?php echo esc_html( $address['zip'] ); ?></span>
							<div class="lpf-country"><?php echo esc_html( $address['country'] ); ?></div>
						</address>
						<?php } ?>
					</div>
				</div>
			</div><!-- / .lpf-job-listing-meta-container -->
			<?php
		endif;
		if ( $this->show_application_button && ( 'true' === $this->show_application_button || '1' === $this->show_application_button ) && ! empty( $job->get_application() ) ) :
			?>
			<div class="lpf-jobs-list-application-link">
				<a href="<?php echo esc_url( $job->get_application_url() ); ?>">
					<button type="button" class="lpf-jobs-list-application-button">
						<?php echo esc_html( $this->button_text ); ?>
					</button>
				</a>
			</div>
			<?php
		endif;
		?>

		</li>
		<?php
	}

	/**
	 * Fires after displaying all of the Jobs.
	 *
	 * @param Job[] $jobs The array of Job objects.
	 */
	do_action( 'lpf_jobs_after', $jobs );
	?>
</ul>
