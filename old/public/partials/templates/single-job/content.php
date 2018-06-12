<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/single-job/content.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section class="job-content <?php echo esc_attr( implode( ' ', lpf_job_classes( '', '', get_the_ID() ) ) ); ?>">
	<?php the_content(); ?>
</section>
