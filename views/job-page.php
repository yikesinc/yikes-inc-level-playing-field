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

$jobs = $this->jobs;

global $post;
?>

<div class="job-page">

	<?php do_action( 'yikes_level_playing_field_jobs_before', $jobs, $post ); ?>

	<?php foreach ( $jobs as $job ):
		$job->load_lazy_properties(); ?>
		<h4 class="job-page-job-title">
			<a href="<?php echo esc_url( get_permalink( $job->get_post_id() ) ); ?>" alt="<?php esc_attr_e( 'link to single job', 'yikes-level-playing-field' ); ?>">
				<?php echo esc_html( $job->get_title() ); ?>
			</a>
		</h4>
	<?php endforeach; ?>

	<?php do_action( 'yikes_level_playing_field_jobs_after', $jobs, $post ); ?>
</div>