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
 * This is the current job.
 *
 * Storing as a custom variable here is not needed, but we hope it is less confusing for
 * those looking to extend this template.
 *
 * @var Job $job
 */
$job = $this->job;

?>
<div class="lpf-jobs-list-application-link">
	<a href="<?php echo esc_url( $job->get_application_url() ); ?>">
		<button type="button" class="lpf-jobs-list-application-button">
			<?php echo esc_html( $this->button_text ); ?>
		</button>
	</a>
</div>
<?php
