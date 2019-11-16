<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * This is the template for the [lpf_all_jobs] shortcode.
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Job;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

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

$show_desc               = filter_var( $this->show_desc, FILTER_VALIDATE_BOOLEAN );
$show_details            = filter_var( $this->show_details, FILTER_VALIDATE_BOOLEAN );
$show_application_button = filter_var( $this->show_application_button, FILTER_VALIDATE_BOOLEAN );
/**
 * Filter the jobs displayed in the job listings shortcode.
 *
 * @since 1.0.0
 *
 * @param array $jobs Array of Job objects.
 *
 * @return array Array of Job objects, maybe filtered.
 */
$jobs = apply_filters( 'lpf_job_listings_jobs', $this->jobs );

foreach ( $jobs as $job ) {
	?>
	<li class="lpf-jobs-list-item">
		<h4 class="lpf-jobs-list-job-title">
			<a href="<?php echo esc_url( get_permalink( $job->get_id() ) ); ?>">
				<?php echo esc_html( $job->get_title() ); ?>
			</a>
		</h4>
		<?php
		if ( $show_desc && ( ! empty( $job->get_content() ) ) ) :
			?>
			<div class="lpf-jobs-list-description">
				<?php
				global $post;
				$post = $job->get_post_object(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				setup_postdata( $post );
				echo 'full' === $this->desc_type ? apply_filters( 'lpf_the_content', $job->get_content() ) : $job->get_excerpt();
				wp_reset_postdata();
				?>
			</div>
			<?php
		endif;
		if ( $show_details ) :
			echo $this->render_partial(
				$this->partials['job_details'],
				array_merge( $this->_context_, [ 'job' => $job ] )
			);
		endif;
		if ( $show_application_button && ! empty( $job->get_application() ) ) :
			echo $this->render_partial(
				$this->partials['job_apply_button'],
				array_merge( $this->_context_, [ 'job' => $job ] )
			);
		endif;
		?>
	</li>
	<?php
}
