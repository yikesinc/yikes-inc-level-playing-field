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
$jobs = $this->jobs;

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

		if ( $this->show_application_button && ! empty( $job->get_application() ) ) :
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
