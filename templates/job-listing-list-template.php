<?php
/**
 * Generate the active job listing List
 * @since 1.0.0
 */
?>
<ul class="yikes-inc-job-listing-list">
	<?php
		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<li>
				<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
					<?php echo esc_attr( get_the_title() ); ?>
				</a>
				-
				<?php echo yikes_format_money( get_post_meta( get_the_ID(), '_compensation_details', true ) ); ?>
			</li>
			<?php
		}
	?>
</ul>
