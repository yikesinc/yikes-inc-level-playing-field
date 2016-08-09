<?php
/**
 * Generate the active job listing Table
 * @since 1.0.0
 */
?>
<table class="table yikes-inc-job-listing-table" data-sorting="true">
	<thead>
		<tr>
			<?php
				// Loop over, build and render the table headers
				if ( $table_headers && ! empty( $table_headers ) ) {
					foreach ( $table_headers as $header_name => $attributes ) {
						?>
							<th data-breakpoints="<?php echo esc_attr( $attributes['break_point'] ); ?>" data-type="<?php echo esc_attr( $attributes['type'] ); ?>">
								<?php echo esc_attr( $header_name ); ?>
							</th>
						<?php
					}
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				?><tr><?php
				// Loop over the table headers again
				foreach ( $table_headers as $header_name => $attributes ) {
					$meta_value = ( 'title' === $attributes['meta_key'] ) ? get_the_title() : ( ( get_post_meta( get_the_ID(), $attributes['meta_key'], true ) ) ? get_post_meta( get_the_ID(), $attributes['meta_key'], true ) : '' );
					// Setup our number/money formats
					// $meta_value = ( is_numeric( $meta_value ) ) ? ( ( '_compensation_details' === $attributes['meta_key'] ) ? get_option( 'yikes_level_playing_field_money_format', '$' ) . number_format_i18n( $meta_value, 2 ) : number_format_i18n( $meta_value, 2 ) ) : $meta_value;
					$meta_value = ( is_numeric( $meta_value ) ) ? ( ( '_compensation_details' === $attributes['meta_key'] ) ? yikes_format_money( $meta_value ) : number_format_i18n( $meta_value, 2 ) ) : $meta_value;
					?>
						<td><?php echo esc_attr( $meta_value ); ?></td>
					<?php
				}
				?></tr><?php
			}
		?>
	</tbody>
</table>
