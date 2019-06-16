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

?>
<div class="lpf-jobs-by-category-list">
	<?php
	foreach ( $this->job_cats as $job_cat_id => $job_cat ) {
		?>
		<h3 class="lpf-jobs-by-category-header"><?php echo esc_html( $job_cat ); ?></h3>
		<ul class="lpf-jobs-list">
			<?php
			echo $this->render_partial( $this->partials['jobs_loop'], [ 'jobs' => $this->jobs_by_cat[ $job_cat_id ] ] ); // phpcs:ignore WordPress.Security.EscapeOutput
			?>
		</ul>
		<?php
	}
	?>
</div>
<?php
