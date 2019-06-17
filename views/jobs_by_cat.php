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
			echo $this->render_partial( // phpcs:ignore WordPress.Security.EscapeOutput
				$this->partials['jobs_loop'],
				[
					'jobs'                    => $this->jobs[ $job_cat_id ], // phpcs:ignore WordPress.Security.EscapeOutput
					'show_desc'               => $this->show_desc, // phpcs:ignore WordPress.Security.EscapeOutput
					'show_details'            => $this->show_details, // phpcs:ignore WordPress.Security.EscapeOutput
					'details_text'            => $this->details_text, // phpcs:ignore WordPress.Security.EscapeOutput
					'job_type_text'           => $this->job_type_text, // phpcs:ignore WordPress.Security.EscapeOutput
					'location_text'           => $this->location_text, // phpcs:ignore WordPress.Security.EscapeOutput
					'remote_location_text'    => $this->remote_location_text, // phpcs:ignore WordPress.Security.EscapeOutput
					'show_application_button' => $this->show_application_button, // phpcs:ignore WordPress.Security.EscapeOutput
					'button_text'             => $this->button_text, // phpcs:ignore WordPress.Security.EscapeOutput
				]
			);
			?>
		</ul>
		<?php
	}
	?>
</div>
<?php
