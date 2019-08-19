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

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<div class="lpf-jobs-by-category-list">
	<?php
	foreach ( $this->job_cats as $job_cat_id => $job_cat ) {
		?>
		<h3 class="lpf-jobs-by-category-header"><?php echo esc_html( $job_cat ); ?></h3>
		<ul class="lpf-jobs-list">
			<?php
			echo $this->render_partial(
				$this->partials['jobs_loop'],
				array_merge( $this->_context_, [ 'jobs' => $this->jobs[ $job_cat_id ] ] )
			);
			?>
		</ul>
		<?php
	}
	?>
</div>
<?php
