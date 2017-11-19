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
	<h4 class="job-page-job-title"><?php echo $job->get_title(); ?></h4>
	<div class="job-page-job-meta">
		<?php if ( ! empty( $job->get_description() ) ) : ?>
			<div class="job-page-job-description">
				<?php echo wpautop( $job->get_description() ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $job->get_type() ) ) : ?>
			<div class="job-page-job-type">
				<?php esc_html_e( 'Type:' ); ?> <?php echo $job->get_type(); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! $job->is_remote() && ! empty( $job->get_address() ) ) : ?>
			<div class="job-page-job-address">
				<!-- Output address -->
			</div>
		<?php endif; ?>
	</div>
	<!-- Output application form -->
</div>
