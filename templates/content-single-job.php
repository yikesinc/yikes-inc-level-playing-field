<?php
/**
 * The template for displaying job posting content in the single-job.php template
 *
 * This template can be overridden by copying it to yourtheme/level-playing-field/content-single-job.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * yikes_level_playing_field_before_single_job hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'yikes_level_playing_field_before_single_job' );

// check if job posting requires a password to be input
if ( post_password_required() ) {
	echo wp_kses_post( get_the_password_form() );
	return;
}
?>

<div itemscope itemtype="" id="job-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * yikes_level_playing_field_before_single_job_summary hook.
		 *
		 * @hooked yikes_level_playing_field_before_single_job - 10
		 */
		do_action( 'yikes_level_playing_field_before_single_job_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * yikes_level_playing_field_single_job_summary hook.
			 *
			 * @hooked yikes_lpf_categories - 10
			 * @hooked yikes_lpf_tags - 11
			 * @hooked yikes_lpf_posted_on - 12
			 */
			do_action( 'yikes_level_playing_field_single_job_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * yikes_level_playing_field_after_single_job_summary hook.
		 *
		 * @hooked append_job_listing_application - 10
		 */
		do_action( 'yikes_level_playing_field_after_single_job_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'yikes_level_playing_field_after_single_job' ); ?>
