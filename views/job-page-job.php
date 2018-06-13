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
$job = $this->job;
?>

<div class="job-page-job">

	<?php if ( apply_filters( 'lpf_single_job_template_show_title', true, $job ) === true ): ?>
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

			<?php if ( $job->is_remote() ) {

				esc_html_e( 'Address: remote', 'yikes-level-playing-field' );

			} else if ( ! empty( $address = $job->get_address() ) ) { ?>

				<address id="lpf-address">

					<div id="lpf-address1"><?php echo esc_html( $address['address-1'] ); ?></div>

					<div id="lpf-address2"><?php echo esc_html( $address['address-2'] ); ?></div>

					<span id="lpf-city"><?php echo esc_html( $address['city'] ); ?></span><?php if ( ! empty( $address['state'] ) ){ ?><span id="lpf-city-state-comma">,</span><?php } ?>

					<span id="lpf-province"><?php echo esc_html( $address['province'] ); ?></span><?php if ( ! empty( $address['province'] ) ) { ?><span id="lpf-city-province-comma">,</span><?php } ?>

					<span id="lpf-city"><?php echo esc_html( $address['state'] ); ?></span>

					<div id="lpf-country"><?php echo esc_html( $address['country'] ); ?></div>
					<div id="lpf-zip"><?php echo esc_html( $address['zip'] ); ?></div>

				</address>

			<?php }; ?>

		</div>

	</div>
	<!-- Output application form -->
</div>
