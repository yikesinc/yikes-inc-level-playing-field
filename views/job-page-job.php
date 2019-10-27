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
use Yikes\LevelPlayingField\View\View;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/** @var Job $job */
$job       = $this->job;
$use_comma = (bool) apply_filters( 'lpf_single_job_template_address_use_comma', true, $job );
?>

<div class="lpf-job-listing">
	<?php if ( $this->show_title ) : ?>
		<h3 class="lpf-job-listing-title"><?php echo esc_html( $job->get_title() ); ?></h3>
	<?php endif; ?>
	<?php
	if ( $this->show_description && ! empty( $job->get_content() ) ) :
		?>
		<div class="lpf-job-listing-description-container">
			<h4 class="lpf-job-listing-description-header"><?php echo esc_html( $this->description_text ); ?></h4>
			<div class="lpf-job-listing-description">
				<?php echo wp_kses_post( apply_filters( 'lpf_the_content', $job->get_content() ) ); ?>
			</div>
		</div>
		<?php
	endif;
	echo $this->render_partial( $this->partials['job_details'] );

	/**
	 * Fires after displaying basic job listing information.
	 *
	 * @param View  $view    The current view object.
	 * @param array $context The context for the current view.
	 */
	do_action( 'lpf_job_after', $this, $this->_context_ );

	if ( $this->show_application_button && ! empty( $job->get_application() ) ) :
		echo $this->render_partial( $this->partials['job_apply_button'] );
	endif;
	?>
</div>
<?php
